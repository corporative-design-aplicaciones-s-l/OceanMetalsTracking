@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Horas Diarias</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Flecha Mes Anterior -->
            <a href="{{ route('workdays.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                class="btn btn-outline-success">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h4>{{ ucfirst($currentMonth) }}</h4>

            <!-- Flecha Mes Siguiente -->
            @if ($isCurrentMonth)
                <button class="btn btn-outline-secondary" disabled>
                     <i class="bi bi-chevron-right"></i>
                </button>
            @else
                <a href="{{ route('workdays.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                    class="btn btn-outline-success">
                     <i class="bi bi-chevron-right"></i>
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Inicio </th>
                        <th>Fin</th>
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
                            <td>{{ $workday['end_time'] }}</td>
                            <td>{{ $workday['break_minutes'] }} '</td>
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
    </div>
@endsection
