<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Index as EventsIndex;
use App\Http\Requests\Events\Store as EventsStore;
use App\Http\Requests\Events\Update as EventsUpdate;
use App\Http\Requests\Events\Destroy as EventsDestroy;
use App\Models\Event;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
{
    public function index(EventsIndex $request)
    {
        $events = Event::query()
            ->when(!auth()->user()->hasRole('admin'), function(Builder $query) {
                $query->where('user_id', '=', auth()->user()->id);
            })
            ->get();

        $events->transform(function ($item, $key) {
            if (isset($item->patient_id)) {
                $patient = Patient::findOrFail($item->patient_id);
                if (isset($patient->hash_file_name)) {
                    $patient->image_path = Storage::disk('patient_avatars')->url($patient->hash_file_name);
                }
                $item->patient = $patient;
            }

            if (isset($item->user_id)) {
                $user = User::findOrFail($item->user_id);
                $item->user = $user;
            }

            return $item;
        });

        return response()->api($events);
    }

    public function store(EventsStore $request)
    {
        $event = Event::create([
            'patient_id' => $request->patient_id,
            'user_id' => auth()->user()->id,
            'start' => $request->start,
            'end' => $request->end,
            'status' => $request->status,
            'details' => $request->details ?? null,
        ]);

        return response()->api($event);
    }

    public function update(EventsUpdate $request, Event $event)
    {
        $event->update([
            'patient_id' => $request->patient_id ?? $event->patient_id,
            'start' => $request->start ?? $event->start,
            'end' => $request->end ?? $event->end,
            'status' => $request->status ?? $event->status,
            'details' => $request->details ?? $event->details,
        ]);

        return response()->api($event);
    }

    public function destroy(EventsDestroy $request, Event $event)
    {
        $event->delete();

        return response()->api($event);
    }
}
