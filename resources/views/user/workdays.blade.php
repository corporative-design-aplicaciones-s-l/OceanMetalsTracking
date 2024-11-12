@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Horas Diarias</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('workdays.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                class="btn btn-outline-secondary">
                <i class="bi bi-chevron-left"></i> Mes Anterior
            </a>
            <h4>{{ $currentMonth }}</h4>
            <a href="{{ route('workdays.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                class="btn btn-outline-secondary">
                Mes Siguiente <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Inicio Jornada</th>
                    <th>Fin Jornada</th>
                    <th>Descanso</th>
                    <th>Total Horas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($workdays as $workday)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($workday['date'])->format('d') }} -
                            {{ ucfirst($workday['day_of_week']) }}</td>
                        <td>{{ $workday['start_time'] }}</td>
                        <td>{{ $workday['end_time'] ?? '--:--' }}</td>
                        <td>{{ $workday['break_minutes'] }} minutos</td>
                        <td>{{ $workday['total_hours'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay registros para este mes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
