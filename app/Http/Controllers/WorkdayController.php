<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Workday;
use Carbon\Carbon;

class WorkdayController extends Controller
{
    public function index(Request $request, $year = null, $month = null)
    {
        $user = Auth::user();

        // Determinar el mes y año actual si no se proporcionan
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;

        // Obtener todas las jornadas del mes actual para el usuario
        $workdays = Workday::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        // Agrupar las jornadas por día y calcular el total de horas sin descanso
        $workdays = $workdays->map(function ($workday) {
            $start = Carbon::parse($workday->date . ' ' . $workday->start_time);
            $end = Carbon::parse($workday->date . ' ' . $workday->end_time);

            // Calcular la duración total sin incluir el descanso
            $duration = $end->diffInMinutes($start) - $workday->break_minutes;
            $hours = intdiv($duration, 60);
            $minutes = $duration % 60;

            return [
                'date' => $workday->date,
                'day_of_week' => Carbon::parse($workday->date)->locale('es')->isoFormat('dddd'), // Día de la semana
                'start_time' => $workday->start_time,
                'end_time' => $workday->end_time,
                'break_minutes' => $workday->break_minutes,
                'total_hours' => "{$hours}h {$minutes}m",
            ];
        });

        return view('user.workdays', [
            'workdays' => $workdays,
            'currentMonth' => Carbon::create($year, $month)->format('F Y'),
            'prevMonth' => Carbon::create($year, $month)->subMonth(),
            'nextMonth' => Carbon::create($year, $month)->addMonth(),
        ]);
    }

    // Método para iniciar la jornada laboral
    public function startWork()
    {
        $user = Auth::user();

        // Crear un nuevo registro de jornada laboral
        $workday = Workday::create([
            'user_id' => $user->id,
            'date' => Carbon::now()->toDateString(),
            'start_time' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Jornada iniciada', 'workday' => $workday]);
    }

    // Método para terminar la jornada laboral
    public function endWork(Request $request)
    {
        $user = Auth::user();

        // Encontrar la jornada laboral del día actual para el usuario
        $workday = Workday::where('user_id', $user->id)
            ->where('date', Carbon::now()->toDateString())
            ->first();

        if ($workday && !$workday->end_time) {
            $workday->end_time = Carbon::now()->toTimeString();
            $workday->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Jornada terminada', 'workday' => $workday]);
    }

    // Método para registrar el descanso
    public function applyBreak(Request $request)
    {
        $user = Auth::user();
        $breakMinutes = $request->input('break_minutes');

        // Encontrar la jornada laboral del día actual para el usuario
        $workday = Workday::where('user_id', $user->id)
            ->where('date', Carbon::now()->toDateString())
            ->first();

        if ($workday) {
            // Actualizar los minutos de descanso, sin superar 180 minutos en total
            $workday->break_minutes = min($workday->break_minutes + $breakMinutes, 180);
            $workday->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Descanso aplicado', 'workday' => $workday]);
    }
}