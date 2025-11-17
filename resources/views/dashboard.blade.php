@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Reservas Hoy</h5>
                        <h2 class="text-primary">{{ $stats['reservas_hoy'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Total Clientes</h5>
                        <h2 class="text-success">{{ $stats['total_clientes'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Mesas Disponibles</h5>
                        <h2 class="text-info">{{ $stats['mesas_disponibles'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chair fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Reservas Pendientes</h5>
                        <h2 class="text-warning">{{ $stats['reservas_pendientes'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Reservations -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservas para Hoy</h5>
            </div>
            <div class="card-body">
                @if($reservas_hoy->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Mesa</th>
                                    <th>Hora</th>
                                    <th>Personas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservas_hoy as $reserva)
                                <tr>
                                    <td>{{ $reserva->cliente->nombre_completo }}</td>
                                    <td>Mesa {{ $reserva->mesa->numero_mesa }}</td>
                                    <td>{{ $reserva->hora_reserva }}</td>
                                    <td>{{ $reserva->numero_personas }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reserva->estado == 'confirmada' ? 'success' : 'warning' }}">
                                            {{ ucfirst($reserva->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay reservas para hoy.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection