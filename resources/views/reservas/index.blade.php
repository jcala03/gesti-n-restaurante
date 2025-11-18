@extends('layouts.app')

@section('page-title', 'Gestión de Reservas')

@section('header-buttons')
    <a href="{{ route('reservas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva Reserva
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($reservas->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
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
                        @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>
                            <td>
                                <a href="{{ route('clientes.show', $reserva->cliente) }}" class="text-decoration-none">
                                    {{ $reserva->cliente->nombre }}
                                </a>
                            </td>
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
                                <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($reserva->estado == 'confirmada')
                                <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Estás seguro de cancelar esta reserva?')"
                                            title="Cancelar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
        @else
            <div class="text-center py-4">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4>No hay reservas registradas</h4>
                <p class="text-muted">Comienza creando tu primera reserva.</p>
                <a href="{{ route('reservas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Primera Reserva
                </a>
            </div>
        @endif
    </div>
</div>
@endsection