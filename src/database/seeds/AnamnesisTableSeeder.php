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
            return compact('name');
        };

        foreach (array_map($toArr, $names) as $key => $value) {
            Anamnesis::updateOrCreate($value);
        }
    }
}
