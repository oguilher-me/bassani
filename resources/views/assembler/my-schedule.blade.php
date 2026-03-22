@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Minha Agenda - {{ $assembler->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h2>Agenda Diária ({{ now()->format('d/m/Y') }})</h2>
        </div>
        <div class="card-body">
            @if ($dailySchedules->isEmpty())
                <p>Nenhum agendamento para hoje.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Endereço</th>
                                <th>Status</th>
                                <th>Observações</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailySchedules as $schedule)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                    <td>{{ $schedule->sale->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->sale->customer->address ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($schedule->pivot->confirmation_status) }}</td>
                                    <td>
                                        <form action="{{ route('assembler.my-schedule.notes') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                                            <textarea name="notes" class="form-control" rows="2">{{ $schedule->pivot->assembler_notes }}</textarea>
                                            <button type="submit" class="btn btn-sm btn-info mt-1">Salvar</button>
                                        </form>
                                    </td>
                                    <td>
                                        @if ($schedule->pivot->confirmation_status == 'pending')
                                            <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                                            </form>
                                            <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                            </form>
                                        @elseif ($schedule->pivot->confirmation_status == 'confirmed')
                                            <a href="{{ route('assembler.my-schedule.start.form', $schedule->id) }}" class="btn btn-sm btn-primary">Iniciar Montagem</a>
                                        @elseif ($schedule->pivot->confirmation_status == 'started')
                                            <a href="{{ route('assembler.my-schedule.finish.form', $schedule->id) }}" class="btn btn-sm btn-success">Concluir Montagem</a>
                                        @else
                                            <span class="badge bg-{{ $schedule->pivot->confirmation_status == 'confirmed' ? 'success' : 'danger' }}">
                                                {{ ucfirst($schedule->pivot->confirmation_status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Agenda Semanal ({{ \Carbon\Carbon::now()->startOfWeek()->format('d/m/Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d/m/Y') }})</h2>
        </div>
        <div class="card-body">
            @if ($weeklySchedules->isEmpty())
                <p>Nenhum agendamento para esta semana.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Endereço</th>
                                <th>Status</th>
                                <th>Observações</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($weeklySchedules as $schedule)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                    <td>{{ $schedule->sale->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->sale->customer->address ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($schedule->pivot->confirmation_status) }}</td>
                                    <td>
                                        <form action="{{ route('assembler.my-schedule.notes') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                                            <textarea name="notes" class="form-control" rows="2">{{ $schedule->pivot->assembler_notes }}</textarea>
                                            <button type="submit" class="btn btn-sm btn-info mt-1">Salvar</button>
                                        </form>
                                    </td>
                                    <td>
                                        @if ($schedule->pivot->confirmation_status == 'pending')
                                            <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                                            </form>
                                            <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                            </form>
                                        @elseif ($schedule->pivot->confirmation_status == 'confirmed')
                                            <a href="{{ route('assembler.my-schedule.start.form', $schedule->id) }}" class="btn btn-sm btn-primary">Iniciar Montagem</a>
                                        @elseif ($schedule->pivot->confirmation_status == 'started')
                                            <a href="{{ route('assembler.my-schedule.finish.form', $schedule->id) }}" class="btn btn-sm btn-success">Concluir Montagem</a>
                                        @else
                                            <span class="badge bg-{{ $schedule->pivot->confirmation_status == 'confirmed' ? 'success' : 'danger' }}">
                                                {{ ucfirst($schedule->pivot->confirmation_status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
