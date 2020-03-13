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
                    $date = now()->sub(rand(1, 10), 'day');
                    $service = Service::inRandomOrder()->first();

                    $count = rand(1, 10);
                    $serviceCost = $service->price * $count;
                    $patient->services()->attach($service->id, [ // Fill service history
                        'count' => $count,
                        'service_cost' => $serviceCost,
                        'date' => $date
                    ]);
                    $patient->update([
                        'total_accrued' => $patient->total_accrued + $serviceCost
                    ]);

                    $amount = rand(100, 2000);
                    $patient->payments()->create([ // Fill payment history
                        'amount' => $amount,
                        'date' => $date
                    ]);
                    $patient->update([
                        'total_paid' => $patient->total_paid + $amount
                    ]);
                }
            });
    }
}
