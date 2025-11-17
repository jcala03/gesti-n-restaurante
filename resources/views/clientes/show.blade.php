@extends('layouts.app')

@section('page-title', 'Detalles del Cliente')

@section('header-buttons')
    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning me-2">
        <i class="fas fa-edit me-2"></i>Editar
    </a>
    <a href="{{ route('reservas.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva Reserva
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Información Personal</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
                <p><strong>Email:</strong> {{ $cliente->email }}</p>
                <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                <p><strong>Cliente desde:</strong> {{ $cliente->created_at->format('d/m/Y') }}</p>
                <p><strong>Total de reservas:</strong> <span class="badge bg-primary">{{ $historial->count() }}</span></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Historial de Reservas</h5>
            </div>
            <div class="card-body">
                @if($historial->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Mesa</th>
                                    <th>Personas</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historial as $reserva)
                                <tr>
                                    <td>{{ $reserva->fecha_reserva->format('d/m/Y') }}</td>
                                    <td>{{ $reserva->hora_reserva }}</td>
                                    <td>Mesa {{ $reserva->mesa->numero_mesa }}</td>
                                    <td>{{ $reserva->numero_personas }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reserva->estado == 'confirmada' ? 'success' : ($reserva->estado == 'cancelada' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($reserva->estado) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($reserva->precio_total, 0, ',', '.') }}</td>
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
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>No hay reservas registradas</h5>
                        <p class="text-muted">Este cliente no tiene historial de reservas.</p>
                        <a href="{{ route('reservas.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Crear Primera Reserva
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection