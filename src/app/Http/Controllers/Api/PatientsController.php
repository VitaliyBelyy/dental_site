<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\Index as PatientsIndex;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

class PatientsController extends Controller
{
    public function index(PatientsIndex $request)
    {
        $limit = $request->get('limit') ?? 10;
        $sortBy = $request->get('sort_by') ?? [];
        $sortDesc = $request->get('sort_desc') ?? [];
        $users = Patient::query()
            ->when(!auth()->user()->hasRole('admin'), function(Builder $query) {
                $query->where('user_id', '=', auth()->user()->id);
            })
            ->when($request->get('q'), function(Builder $query) use (&$request) {
                $query->where('id', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('first_name', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('last_name', 'LIKE', '%'. $request->get('q') .'%');
            })
            ->with('anamnesis');

        foreach ($sortBy as $index => $column) {
            $direction = isset($sortDesc[$index]) ? json_decode($sortDesc[$index]) : false;
            $users->orderBy($column, $direction ? 'DESC' : 'ASC');
        }

        return response()->api($users->paginate($limit));
    }
}
