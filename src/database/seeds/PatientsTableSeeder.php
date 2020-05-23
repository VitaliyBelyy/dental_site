<?php

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Tooth;

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
                // Fill visit history
                for ($i = 0; $i < 10; $i++) {
                    $date = now()->sub(10 - $i, 'day');
                    $visit = $patient->visits()->create([
                        'date' => $date
                    ]);
                    $iterationsNum = rand(1, 3);
                    $serviceCost = 0;

                    for ($j = 0; $j < $iterationsNum; $j++) {
                        $tooth = Tooth::inRandomOrder()->first();
                        $service = Service::inRandomOrder()->first();
                        $serviceCount = rand(1, 3);
                        $totalCost = $service->price * $serviceCount;

                        $visit->services()->attach($service->id, [
                            'tooth_id' => $tooth->id,
                            'service_count' => $serviceCount,
                            'total_cost' => $totalCost
                        ]);
                        $serviceCost += $totalCost;
                    }
                    $patient->update([
                        'total_accrued' => $patient->total_accrued + $serviceCost
                    ]);
                }

                // Fill payment history
                for ($i = 0; $i < 10; $i++) {
                    $date = now()->sub(10 - $i, 'day');
                    $amount = rand(1000, 5000);

                    $patient->payments()->create([
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
