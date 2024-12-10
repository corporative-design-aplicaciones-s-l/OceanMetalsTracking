<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workday;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AdminController extends Controller
{

    public function index()
    {
        $currentDate = Carbon::now()->toDateString();

        // Obtener el estado de todos los trabajadores
        $workers = User::where('role', 'trabajador')->get();

        // Obtener los estados de los trabajadores
        $workerStatuses = $this->getWorkerStatuses($workers, $currentDate);

        // Solicitudes de vacaciones no validadas ni rechazadas
        $vacationRequests = Vacation::where('validated', false)
            ->where('refused', false)
            ->get();

        // Trabajadores y sus estadísticas
        $workerDetails = $this->getWorkerDetails($workers);

        // Cantidad para el Pie-Chart
        $chartData = [
            'trabajando' => count($workerStatuses['trabajando']),
            'no_trabajando' => count($workerStatuses['no_trabajando']),
            'descansando' => count($workerStatuses['descansando']),
            'de_vacaciones' => count($workerStatuses['de_vacaciones']),
        ];

        return view('admin.dashboard', compact('workerStatuses', 'vacationRequests', 'workerDetails', 'chartData'));
    }

    protected function getWorkerStatuses($workers, $currentDate)
    {
        $workerStatuses = [
            'trabajando' => [],
            'no_trabajando' => [],
            'descansando' => [],
            'de_vacaciones' => [],
        ];

        $workers->each(function ($worker) use ($currentDate, &$workerStatuses) {
            // Verificar si está de vacaciones
            $vacation = Vacation::where('user_id', $worker->id)
                ->where('validated', true)
                ->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)
                ->first();

            if ($vacation) {
                $workerStatuses['de_vacaciones'][] = $worker;
            } else {
                // Verificar en la tabla de workdays
                $workday = Workday::where('user_id', $worker->id)
                    ->where('date', $currentDate)
                    ->first();

                if ($workday) {
                    if (!$workday->end_time) {
                        if ($workday->break_start_time) {
                            $breakEndTime = Carbon::parse($workday->break_start_time)
                                ->addMinutes($workday->break_minutes);

                            if (Carbon::now()->lt($breakEndTime)) {
                                $workerStatuses['descansando'][] = $worker;
                            } else {
                                $workerStatuses['trabajando'][] = $worker;
                            }
                        } else {
                            $workerStatuses['trabajando'][] = $worker;
                        }
                    } else {
                        $workerStatuses['no_trabajando'][] = $worker;
                    }
                } else {
                    $workerStatuses['no_trabajando'][] = $worker;
                }
            }
        });

        return $workerStatuses;
    }


    protected function getWorkerDetails($workers)
    {
        $currentDate = Carbon::now()->toDateString();

        return $workers->map(function ($worker) use ($currentDate) {
            // Verificar si está de vacaciones
            $estado = $this->determineWorkerStatus($worker, $currentDate);

            // Detectar la base de datos utilizada
            $dbDriver = DB::connection()->getDriverName();

            if ($dbDriver === 'mysql') {
                $totalHoursWorked = Workday::where('user_id', $worker->id)
                    ->whereNotNull('end_time')
                    ->selectRaw("SUM(TIMESTAMPDIFF(MINUTE, start_time, end_time)) / 60 as total_hours")
                    ->value('total_hours');
            } elseif ($dbDriver === 'sqlite') {
                $totalHoursWorked = Workday::where('user_id', $worker->id)
                    ->whereNotNull('end_time')
                    ->selectRaw("SUM((strftime('%s', end_time) - strftime('%s', start_time)) / 3600) as total_hours")
                    ->value('total_hours');
            } else {
                $totalHoursWorked = 0;
            }

            $totalBreakMinutes = Workday::where('user_id', $worker->id)->sum('break_minutes');
            $vacationsLeft = 30 - Vacation::where('user_id', $worker->id)->where('validated', true)->sum('total_days');

            return [
                'name' => $worker->name,
                'estado' => $estado,
                'total_hours_worked' => number_format($totalHoursWorked ?? 0, 1) . 'h',
                'total_breaks' => "{$totalBreakMinutes}m",
                'vacations_left' => "{$vacationsLeft} días restantes",
            ];
        });
    }

    protected function determineWorkerStatus($worker, $currentDate)
    {
        $vacation = Vacation::where('user_id', $worker->id)
            ->where('validated', true)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->first();

        if ($vacation) {
            return 'de_vacaciones';
        }

        $workday = Workday::where('user_id', $worker->id)
            ->where('date', $currentDate)
            ->first();

        if ($workday) {
            if (!$workday->end_time) {
                if ($workday->break_start_time) {
                    $breakEndTime = Carbon::parse($workday->break_start_time)
                        ->addMinutes($workday->break_minutes);

                    return Carbon::now()->lt($breakEndTime) ? 'descansando' : 'trabajando';
                }
                return 'trabajando';
            }
            return 'no_trabajando';
        }

        return 'no_trabajando';
    }

    public function showWorkdays(Request $request, User $worker, $year = null, $month = null)
    {
        // Determinar el mes y año actual si no se proporcionan
        $year ??= Carbon::now()->year;
        $month ??= Carbon::now()->month;

        // Variable para verificar si el mes actual es el que se está visualizando
        $isCurrentMonth = $year == Carbon::now()->year && $month == Carbon::now()->month;

        // Obtener todas las jornadas del mes actual para el usuario
        $workdays = Workday::where('user_id', $worker->id)
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

        return view('admin.workdays', [
            'worker' => $worker,
            'workdays' => $workdays,
            'currentMonth' => Carbon::create($year, $month)->locale('es')->isoFormat('MMMM YYYY'),
            'prevMonth' => Carbon::create($year, $month)->subMonth(),
            'nextMonth' => Carbon::create($year, $month)->addMonth(),
            'isCurrentMonth' => $isCurrentMonth,
        ]);
    }



}
