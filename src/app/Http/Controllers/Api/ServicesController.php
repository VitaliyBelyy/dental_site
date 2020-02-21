<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\Index as ServicesIndex;
use App\Models\Service;

class ServicesController extends Controller
{
    public function index(ServicesIndex $request) {
        $services = Service::all();

        return response()->api($services);
    }
}
