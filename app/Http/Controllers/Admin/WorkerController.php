<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $workers = User::all(); // Obtener todos los usuarios (trabajadores)
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