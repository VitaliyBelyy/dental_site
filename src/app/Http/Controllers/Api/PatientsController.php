<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\Index as PatientsIndex;
use App\Http\Requests\Patients\Store as PatientsStore;
use App\Http\Requests\Patients\Show as PatientsShow;
use App\Http\Requests\Patients\Update as PatientsUpdate;
use App\Http\Requests\Patients\ShowServiceHistory as PatientsShowServiceHistory;
use App\Http\Requests\Patients\CreateServiceHistoryRecord as PatientsCreateServiceHistoryRecord;
use App\Http\Requests\Patients\ShowPaymentHistory as PatientsShowPaymentHistory;
use App\Http\Requests\Patients\CreatePaymentHistoryRecord as PatientsCreatePaymentHistoryRecord;
use App\Models\Anamnesis;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PatientsController extends Controller
{
    public function index(PatientsIndex $request)
    {
        $limit = $request->get('limit') ?? 10;
        $patients = Patient::query()
            ->when(!auth()->user()->hasRole('admin'), function(Builder $query) {
                $query->where('user_id', '=', auth()->user()->id);
            })
            ->when($request->get('q'), function(Builder $query) use (&$request) {
                $query->where('id', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('full_name', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('phone', 'LIKE', '%'. $request->get('q') .'%');
            })
            ->when($request->get('sort_by'), function(Builder $query) use (&$request) {
                $direction = $request->get('sort_desc');
                $query->orderBy($request->get('sort_by'), (isset($direction) && json_decode($direction)) ? 'DESC' : 'ASC');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        $patients->getCollection()->transform(function ($item, $key) {
            if (isset($item->hash_file_name)) {
                $item->image_path = Storage::disk('patient_avatars')->url($item->hash_file_name);
            }

            return $item;
        });

        return response()->api($patients);
    }

    public function store(PatientsStore $request) {
        $photo = $request->file('photo');
        $originalFileName = null;
        $hashFileName = null;

        if (isset($photo)) {
            $imagePath = $photo->store('/', 'patient_avatars');

            if ($imagePath) {
                $originalFileName = $photo->getClientOriginalName();
                $hashFileName = basename($imagePath);
            }
        }

        $patient = Patient::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone ?? null,
            'email' => $request->email ?? null,
            'gender' => $request->gender ?? null,
            'birth_date' => $request->birth_date ?? null,
            'medical_info' => $request->medical_info ?? null,
            'user_id' => auth()->user()->id,
            'anamnesis_id' => $request->anamnesis_id ?? null,
            'original_file_name' => $originalFileName,
            'hash_file_name' => $hashFileName
        ]);

        return response()->api($patient);
    }

    public function show(PatientsShow $request, Patient $patient) {
        if (isset($patient->hash_file_name)) {
            $patient->image_path = Storage::disk('patient_avatars')->url($patient->hash_file_name);
        }
        if (isset($patient->anamnesis_id)) {
            $patient->anamnesis = Anamnesis::find($patient->anamnesis_id);
        }

        return response()->api($patient);
    }

    public function update(PatientsUpdate $request, Patient $patient) {
        $photo = $request->file('photo');
        $originalFileName = $patient->original_file_name;
        $hashFileName = $patient->hash_file_name;

        if (isset($photo)) {
            if (isset($hashFileName)) {
                Storage::disk('patient_avatars')->delete($hashFileName);
            }

            $imagePath = $photo->store('/', 'patient_avatars');

            if ($imagePath) {
                $originalFileName = $photo->getClientOriginalName();
                $hashFileName = basename($imagePath);
            }
        }

        $patient->update([
            'full_name' => $request->full_name ?? $patient->full_name,
            'phone' => $request->phone ?? $patient->phone,
            'email' => $request->email ?? $patient->email,
            'gender' => $request->gender ?? $patient->gender,
            'birth_date' => $request->birth_date ?? $patient->birth_date,
            'medical_info' => $request->medical_info ?? $patient->medical_info,
            'anamnesis_id' => $request->anamnesis_id ?? $patient->anamnesis_id,
            'original_file_name' => $originalFileName,
            'hash_file_name' => $hashFileName
        ]);

        return response()->api($patient);
    }

    public function showServiceHistory(PatientsShowServiceHistory $request, $id) {
        $limit = $request->get('limit') ?? 10;
        $search = $request->get('q');
        $sortBy = $request->get('sort_by');
        $query = DB::table('patient_service')
            ->join('services', 'patient_service.service_id', '=', 'services.id')
            ->where('patient_service.patient_id', '=', $id)
            ->orderBy('patient_service.created_at', 'DESC')
            ->select(DB::raw('patient_service.id, patient_service.count * services.price as cost, services.name, patient_service.date'));

        if (isset($search)) {
            $query = $query->where('services.name', 'LIKE', '%' . $request->get('q') . '%');
        }

        if (isset($sortBy)) {
            $direction = $request->get('sort_desc');
            $query = $query->orderBy($request->get('sort_by'), (isset($direction) && json_decode($direction)) ? 'DESC' : 'ASC');
        }

        $history = $query->paginate($limit);

        return response()->api($history);
    }

    public function createServiceHistoryRecord(PatientsCreateServiceHistoryRecord $request, Patient $patient) {
        $patient->services()->attach($request->service_id, [
            'count' => $request->count, 'date' => $request->date
        ]);

        return response()->api(null, 200);
    }

    public function showPaymentHistory(PatientsShowPaymentHistory $request, Patient $patient) {
        $limit = $request->get('limit') ?? 10;
        $payments = $patient->payments()
            ->when($request->get('sort_by'), function(Builder $query) use (&$request) {
                $direction = $request->get('sort_desc');
                $query->orderBy($request->get('sort_by'), (isset($direction) && json_decode($direction)) ? 'DESC' : 'ASC');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->api($payments);
    }

    public function createPaymentHistoryRecord(PatientsCreatePaymentHistoryRecord $request, Patient $patient) {
        $result = $patient->payments()->create([
            'amount' => $request->amount,
            'date' => $request->date,
            'notes' => $request->notes ?? null,
        ]);

        return response()->api($result);
    }
}
