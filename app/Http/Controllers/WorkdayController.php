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
        $year ??= Carbon::now()->year;
        $month ??= Carbon::now()->month;

        // Variable para verificar si el mes actual es el que se está visualizando
        $isCurrentMonth = $year == Carbon::now()->year && $month == Carbon::now()->month;

        // Obtener todas las jornadas del mes actual para el usuario
        $workdays = Workday::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        // Agrupar las jornadas por día y calcular el total de horas sin descanso
        $workdays = $workdays->map(function ($workday) {
            $start = Carbon::parse("{$workday->date} {$workday->start_time}");

            // Si la jornada no ha terminado, mostrar '0h 0m' para total_hours
            if (!$workday->end_time) {
                return [
                    'date' => $workday->date,
                    'day_of_week' => Carbon::parse($workday->date)->locale('es')->isoFormat('dddd'),
                    'start_time' => $workday->start_time,
                    'end_time' => '--:--',
                    'break_minutes' => $workday->break_minutes,
                    'total_hours' => '0h 0m',
                ];
            }

            $end = Carbon::parse("{$workday->date} {$workday->end_time}");

            // Calcular la duración total sin incluir el descanso
            $duration = $start->diffInMinutes($end) - $workday->break_minutes;
            $duration = max(0, $duration); // Asegurarse de que no sea negativo


            $hours = intdiv($duration, 60);
            $minutes = $duration % 60;

            return [
                'date' => $workday->date,
                'day_of_week' => Carbon::parse($workday->date)->locale('es')->isoFormat('dddd'),
                'start_time' => $workday->start_time,
                'end_time' => $workday->end_time,
                'break_minutes' => $workday->break_minutes,
                'total_hours' => "{$hours}h {$minutes}m",
            ];
        });

        return view('user.workdays', [
            'workdays' => $workdays,
            'currentMonth' => Carbon::create($year, $month)->locale('es')->isoFormat('MMMM YYYY'),
            'prevMonth' => Carbon::create($year, $month)->subMonth(),
            'nextMonth' => Carbon::create($year, $month)->addMonth(),
            'isCurrentMonth' => $isCurrentMonth,
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

        // Encontrar la última jornada laboral del día actual para el usuario sin hora de fin
        $workday = Workday::where('user_id', $user->id)
            // ->where('date', Carbon::now()->toDateString())
            ->whereNull('end_time')
            ->orderBy('start_time', 'desc')
            ->first();

        if ($workday) {
            $workday->end_time = Carbon::now()->toTimeString();
            $workday->save();
            return response()->json(['status' => 'success', 'message' => 'Jornada terminada', 'workday' => $workday]);
        }

        return response()->json(['status' => 'error', 'message' => 'No hay jornada en curso para finalizar'], 400);
    }

    // Método para registrar el descanso TODO:
    public function applyBreak(Request $request)
    {
        $user = Auth::user();
        $breakMinutes = $request->input('break_minutes');

        // Encontrar la jornada laboral del día actual para el usuario
        $workday = Workday::where('user_id', $user->id)
            ->where('date', Carbon::now()->toDateString())
            ->first();

        if ($workday) {
            // Registrar la hora de inicio del descanso si no existe
            if (!$workday->break_start_time) {
                $workday->break_start_time = Carbon::now();
            }

            // Actualizar los minutos de descanso, sin superar 180 minutos en total
            $workday->break_minutes = min($workday->break_minutes + $breakMinutes, 180);
            $workday->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Descanso aplicado',
            'workday' => $workday,
        ]);
    }

    // Método para verificar el estado de la jornada
    public function checkWorkStatus()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        // Buscar si el usuario tiene una jornada iniciada y sin terminar para el día actual
        $workday = Workday::where('user_id', $user->id)
            ->where('date', $today)
            ->whereNull('end_time')
            ->first();

        if ($workday) {
            return response()->json(['status' => 'started', 'start_time' => $workday->start_time]);
        } else {
            return response()->json(['status' => 'not_started']);
        }
    }
}
