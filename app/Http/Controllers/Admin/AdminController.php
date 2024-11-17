<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workday;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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



}