<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center"
        style="height: 100vh; background-image: url('{{ asset('images/landing/background.png') }}'); background-size: cover; background-position: center;">
        <div class="text-center text-light" style="background-color: rgba(0, 0, 0, 0.9); padding: 20px; border-radius: 10px;">
            <h1>Bienvenido a Tick Track</h1>
            <p>Una plataforma fácil de usar para el seguimiento de asistencia y tiempos de trabajo.</p>
        </div>
    </div>
@endsection
