<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\Index as ServicesIndex;
use App\Http\Requests\Services\Store as ServicesStore;
use App\Http\Requests\Services\Update as ServicesUpdate;
use App\Http\Requests\Services\Destroy as ServicesDestroy;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;

class ServicesController extends Controller
{
    public function index(ServicesIndex $request)
    {
        $limit = $request->get('limit') ?? 10;
        $services = Service::query()
            ->when($request->get('q'), function(Builder $query) use (&$request) {
                $query->where('name', 'LIKE', '%'. $request->get('q') .'%');
            })
            ->when($request->get('sort_by'), function(Builder $query) use (&$request) {
                $direction = $request->get('sort_desc');
                $query->orderBy($request->get('sort_by'), (isset($direction) && json_decode($direction)) ? 'DESC' : 'ASC');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->api($services);
    }

    public function store(ServicesStore $request)
    {
        $service = Service::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->api($service);
    }

    public function update(ServicesUpdate $request, Service $service)
    {
        $service->update([
            'name' => $request->name ?? $service->name,
            'price' => $request->price ?? $service->price,
        ]);

        return response()->api($service);
    }

    public function destroy(ServicesDestroy $request, Service $service)
    {
        $service->delete();

        return response()->api($service);
    }
}
