<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teeth\Index as TeethIndex;
use App\Models\Tooth;
use Illuminate\Support\Facades\Storage;

class TeethController extends Controller
{
    public function index(TeethIndex $request)
    {
        $teeth = Tooth::all();

        $teeth->transform(function ($item, $key) {
            if (isset($item->icon_file_name)) {
                $item->icon_path = Storage::disk('teeth')->url($item->icon_file_name);
            }

            return $item;
        });

        $upperJaw = $teeth->filter(function ($value, $key) { 
            return $value->jaw === Tooth::UPPER_JAW;
        });
        $lowerJaw = $teeth->filter(function ($value, $key) { 
            return $value->jaw === Tooth::LOWER_JAW;
        });

        return response()->api([
            'upper_jaw' => $upperJaw->values()->all(),
            'lower_jaw' => $lowerJaw->values()->all()
        ]);
    }
}
