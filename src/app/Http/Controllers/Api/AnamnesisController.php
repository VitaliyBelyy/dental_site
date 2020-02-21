<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Anamnesis\Index as AnamnesisIndex;
use App\Models\Anamnesis;

class AnamnesisController extends Controller
{
    public function index(AnamnesisIndex $request)
    {
        $anamnesis = Anamnesis::all();

        return response()->api($anamnesis);
    }
}
