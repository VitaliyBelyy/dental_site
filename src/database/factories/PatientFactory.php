<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Patient;
use App\Models\User;
use App\Models\Anamnesis;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    return [
        'full_name' => $faker->name,
        'phone' => $faker->numerify('+38 (0##) ###-##-##'),
        'email' => $faker->unique()->safeEmail,
        'gender' => rand(0, 1),
        'birth_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'medical_info' => $faker->text(250),
        'user_id' => User::inRandomOrder()->first()->id,
        'anamnesis_id' => Anamnesis::inRandomOrder()->first()->id,
    ];
});
