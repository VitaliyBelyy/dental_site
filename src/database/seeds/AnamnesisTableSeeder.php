<?php

use Illuminate\Database\Seeder;
use App\Models\Anamnesis;

class AnamnesisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ["Гипертония", "Гепатит", "Аллергия", "Гипотония", "Гайморит"];

        $toArr = function($name) {
            return [
                'name' => $name,
                'created_at' => now(),
                'updated_At' => now()
            ];
        };

        Anamnesis::insert(array_map($toArr, $names));
    }
}
