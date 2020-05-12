<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Visits\ShowServices as VisitsShowServices;
use App\Models\Visit;

class VisitsController extends Controller
{
    public function showServices(VisitsShowServices $request, Visit $visit) {
        $services = $visit->services()
            ->get();

        return response()->api($services);
    }
}
