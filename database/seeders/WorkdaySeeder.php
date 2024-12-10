<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workday;
use App\Models\User;
use Carbon\Carbon;

class WorkdaySeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'trabajador')->get(); // Obtener solo los trabajadores

        foreach ($users as $user) {
            for ($i = 1; $i <= 30; $i++) { // Simular 30 días de trabajo
                $date = Carbon::now()->subDays(30 - $i); // Generar fechas recientes

                $startHour = rand(6, 9); // Jornada empieza entre las 6 AM y 9 AM
                $workDuration = rand(4, 8); // Duración de trabajo entre 4 y 8 horas
                $breakMinutes = rand(15, 60); // Descanso entre 15 y 60 minutos

                $startTime = Carbon::parse($date)->setHour($startHour)->setMinute(0);
                $endTime = (clone $startTime)->addHours($workDuration);

                Workday::create([
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'start_time' => $startTime->toTimeString(),
                    'end_time' => $endTime->toTimeString(),
                    'break_minutes' => $breakMinutes,
                ]);
            }
        }
    }
}
