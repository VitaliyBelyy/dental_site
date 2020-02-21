<?php

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Service;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Patient::class, 20)->create()
            ->each(function ($patient) {
                for ($i = 0; $i < 10; $i++) {
                    $count = rand(1, 10);
                    $date = now()->sub(rand(1, 10), 'day');
                    $serviceId = Service::inRandomOrder()->first()->id;
                    $patient->services()->attach($serviceId, ['count' => $count, 'date' => $date]); // Fill service history
                    $patient->payments()->create([ // Fill payment history
                        'amount' => rand(100, 500),
                        'date' => $date
                    ]);
                }
            });
    }
}
