<?php

namespace App\Http\Controllers;

use App\Models\Workday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $currentDate = Carbon::now()->toDateString();

        // Obtener todas las jornadas del día actual
        $todayWorkdays = Workday::where('user_id', $user->id)
            ->whereDate('date', $currentDate)
            ->get();

        // Calcular el tiempo total trabajado hoy
        $totalMinutesWorkedToday = 0;
        foreach ($todayWorkdays as $workday) {
            if ($workday->start_time && $workday->end_time) {
                $start = Carbon::parse($workday->date . ' ' . $workday->start_time);
                $end = Carbon::parse($workday->date . ' ' . $workday->end_time);
                $totalMinutesWorkedToday += $end->diffInMinutes($start) - $workday->break_minutes;
            }
        }

        // Asumir que la jornada laboral esperada es de 8 horas (480 minutos)
        $expectedWorkdayMinutes = 480;

        // Calcular el tiempo restante para completar la jornada laboral
        $remainingMinutes = max(0, $expectedWorkdayMinutes - $totalMinutesWorkedToday);

        // Calcular la hora de fin estimada basado en el tiempo restante
        $estimatedEndTime = Carbon::now()->addMinutes($remainingMinutes)->format('H:i');

        return view('home', [
            'estimatedEndTime' => $estimatedEndTime,
            'remainingMinutes' => $remainingMinutes,
        ]);
    }
}
