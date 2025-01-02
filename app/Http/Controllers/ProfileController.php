<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'telefono' => 'nullable|string|min:9|max:15',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->last_name = $request->input('last_name');
        $user->telefono = $request->input('telefono');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('profile.show')->with(['status' => 'success', 'message' => 'Perfil actualizado correctamente.']);
    }
}
