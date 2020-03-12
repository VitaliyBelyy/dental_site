<?php

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusList = ['Запланировано', 'Подтверждено', 'Выполнено', 'Пропущено', 'Перенесено'];
        $startDay = Carbon::now()->startOfMonth()->startOfDay();
        $endDay = Carbon::now()->endOfMonth()->endOfDay();

        while ($startDay->lessThan($endDay)) {
            if ($startDay->isWeekend()) {
                $startDay->addDay();
                continue;
            }

            $visitsCount = rand(1, 4);
            $start = $startDay->copy()->addHours(8);

            for ($i = 0; $i < $visitsCount; $i++) {
                $end = $start->copy()->addHours(rand(1, 2));
                Event::create([
                    'patient_id' => Patient::inRandomOrder()->first()->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'status' => $statusList[array_rand($statusList)],
                ]);
                $interval = rand(1, 5) > 3 ? 1 : 0;
                $start = $end->addHours($interval);
            }

            $startDay->addDay();
        }
    }
}
