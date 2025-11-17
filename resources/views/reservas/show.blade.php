@extends('layouts.app')

@section('page-title', 'Detalles de Reserva')

@section('header-buttons')
    <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning me-2">
        <i class="fas fa-edit me-2"></i>Editar
    </a>
    @if($reserva->estado == 'confirmada')
    <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="d-inline me-2">
        @csrf
        <button type="submit" class="btn btn-danger" 
                onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
            <i class="fas fa-times me-2"></i>Cancelar Reserva
        </button>
    </form>
    @endif
    @if(!$reserva->factura)
    <form action="{{ route('reservas.generar-factura', $reserva) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="fas fa-file-invoice me-2"></i>Generar Factura
        </button>
    </form>
    @else
    <a href="{{ route('facturas.show', $reserva->factura) }}" class="btn btn-info">
        <i class="fas fa-file-invoice me-2"></i>Ver Factura
    </a>
    @endif
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Información de la Reserva</h5>
            </div>
            <div class="card-body">
                <p><strong>ID Reserva:</strong> {{ $reserva->id }}</p>
                <p><strong>Fecha:</strong> {{ $reserva->fecha_reserva->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $reserva->hora_reserva }}</p>
                <p><strong>Número de Personas:</strong> {{ $reserva->numero_personas }}</p>
                <p><strong>Estado:</strong> 
                    <span class="badge bg-{{ $reserva->estado == 'confirmada' ? 'success' : ($reserva->estado == 'cancelada' ? 'danger' : 'warning') }}">
                        {{ ucfirst($reserva->estado) }}
                    </span>
                </p>
                <p><strong>Precio Total:</strong> ${{ number_format($reserva->precio_total, 0, ',', '.') }}</p>
                @if($reserva->notas)
                <p><strong>Notas:</strong> {{ $reserva->notas }}</p>
                @endif
                <p><strong>Creada por:</strong> {{ $reserva->usuario->name }}</p>
                <p><strong>Fecha de creación:</strong> {{ $reserva->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Información del Cliente</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> 
                    <a href="{{ route('clientes.show', $reserva->cliente) }}" class="text-decoration-none">
                        {{ $reserva->cliente->nombre }}
                    </a>
                </p>
                <p><strong>Email:</strong> {{ $reserva->cliente->email }}</p>
                <p><strong>Teléfono:</strong> {{ $reserva->cliente->telefono }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Información de la Mesa</h5>
            </div>
            <div class="card-body">
                <p><strong>Mesa:</strong> {{ $reserva->mesa->numero_mesa }}</p>
                <p><strong>Capacidad:</strong> {{ $reserva->mesa->capacidad }} personas</p>
                <p><strong>Precio Base:</strong> ${{ number_format($reserva->mesa->precio_base, 0, ',', '.') }} por persona</p>
                <p><strong>Estado Mesa:</strong> 
                    <span class="badge bg-{{ $reserva->mesa->disponible ? 'success' : 'danger' }}">
                        {{ $reserva->mesa->disponible ? 'Disponible' : 'No Disponible' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

@if($reserva->factura)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Información de Factura</h5>
            </div>
            <div class="card-body">
                <p><strong>Número de Factura:</strong> {{ $reserva->factura->numero_factura }}</p>
                <p><strong>Subtotal:</strong> ${{ number_format($reserva->factura->subtotal, 0, ',', '.') }}</p>
                <p><strong>Impuestos (19%):</strong> ${{ number_format($reserva->factura->impuestos, 0, ',', '.') }}</p>
                <p><strong>Total:</strong> ${{ number_format($reserva->factura->total, 0, ',', '.') }}</p>
                <p><strong>Estado:</strong> 
                    <span class="badge bg-{{ $reserva->factura->estado == 'pagada' ? 'success' : 'warning' }}">
                        {{ ucfirst($reserva->factura->estado) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection