<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacation;
use App\Models\Workday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Notifications\SetPasswordNotification;
use App\Models\User;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = User::where('role', 'trabajador')->get();

        $workers = $workers->map(function ($worker) {
            $currentDate = Carbon::now()->toDateString();

            // Estado de trabajo por defecto
            $worker->estado_trabajo = 'no_trabajando';

            // Verificar si está de vacaciones
            $vacation = Vacation::where('user_id', $worker->id)
                ->where('validated', true)
                ->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)
                ->first();

            if ($vacation) {
                $worker->estado_trabajo = 'de_vacaciones';
            } else {
                // Verificar en la tabla de workdays
                $workday = Workday::where('user_id', $worker->id)
                    ->where('date', $currentDate)
                    ->first();

                if ($workday) {
                    if (!$workday->end_time) {
                        // Si tiene un inicio de jornada pero no ha finalizado
                        if ($workday->break_start_time) {
                            $breakEndTime = Carbon::parse($workday->break_start_time)
                                ->addMinutes($workday->break_minutes);

                            $worker->estado_trabajo = (Carbon::now()->lt($breakEndTime)) ? 'descansando' : 'trabajando';
                        } else {
                            $worker->estado_trabajo = 'trabajando';
                        }
                    }
                }
            }

            return $worker;
        });

        return view('admin.workers.index', compact('workers'));
    }

    public function edit(User $worker)
    {
        return view('admin.workers.edit', compact('worker'));
    }

    public function destroy(User $worker)
    {
        $worker->delete();
        return redirect()->route('admin.workers.index')->with('success', 'Trabajador eliminado con éxito.');
    }

    public function createWorker()
    {
        return view('auth.register');
    }

    public function registerWorker(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Crear el trabajador con rol de 'trabajador' por defecto
        $worker = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => User::ROLE_TRABAJADOR,
            'password' => Hash::make(Str::random(20)), // Contraseña temporal
        ]);

        // Generar token y enviar el correo de creación de contraseña
        $token = app('auth.password.broker')->createToken($worker);
        $worker->notify(new SetPasswordNotification($token));

        return redirect()->route('admin.workers.index')->with('success', 'Trabajador registrado y correo enviado para creación de contraseña.');
    }

}