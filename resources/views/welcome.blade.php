@extends('layouts.app')
@section('head')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endsection
@section('content')
    <div class="welcome-background d-flex justify-content-center align-items-center">
        <div class="text-center text-light overlay-text">
            <h1>Bienvenido a Tick Track</h1>
            <p>Una plataforma fácil de usar para el seguimiento de asistencia y tiempos de trabajo.</p>
        </div>
    </div>
@endsection
