@extends('layouts.app')

@section('page-title', 'Factura ' . $factura->numero_factura)

@section('header-buttons')
    @if($factura->estado == 'pendiente')
    <form action="{{ route('facturas.marcar-pagada', $factura) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success me-2">
            <i class="fas fa-check me-2"></i>Marcar como Pagada
        </button>
    </form>
    @endif
    <a href="{{ route('facturas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver a Facturas
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <!-- Encabezado de la Factura -->
                <div class="row mb-4">
                    <div class="col-6">
                        <h2>FACTURA</h2>
                        <p class="mb-1"><strong>N°:</strong> {{ $factura->numero_factura }}</p>
                        <p class="mb-1"><strong>Fecha:</strong> {{ $factura->fecha_emision->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Estado:</strong> 
                            <span class="badge bg-{{ $factura->estado == 'pagada' ? 'success' : 'warning' }}">
                                {{ ucfirst($factura->estado) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-6 text-end">
                        <h3>Restaurant Elegante</h3>
                        <p class="mb-1">NIT: 900.123.456-7</p>
                        <p class="mb-1">Dirección: Cra 45 #26-85, Medellín</p>
                        <p class="mb-1">Teléfono: (604) 444 1234</p>
                    </div>
                </div>

                <!-- Información del Cliente -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5>Información del Cliente</h5>
                        <div class="border p-3">
                            <p class="mb-1"><strong>Nombre:</strong> {{ $factura->reserva->cliente->nombre_completo }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $factura->reserva->cliente->email }}</p>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $factura->reserva->cliente->telefono }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Reserva -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5>Detalles de la Reserva</h5>
                        <div class="border p-3">
                            <p class="mb-1"><strong>Fecha Reserva:</strong> {{ $factura->reserva->fecha_reserva->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong>Hora:</strong> {{ $factura->reserva->hora_reserva }}</p>
                            <p class="mb-1"><strong>Mesa:</strong> {{ $factura->reserva->mesa->numero_mesa }}</p>
                            <p class="mb-1"><strong>N° Personas:</strong> {{ $factura->reserva->numero_personas }}</p>
                            @if($factura->reserva->notas)
                            <p class="mb-1"><strong>Notas:</strong> {{ $factura->reserva->notas }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Desglose de Pagos -->
                <div class="row">
                    <div class="col-12">
                        <h5>Desglose de Pagos</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-end">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reserva Mesa {{ $factura->reserva->mesa->numero_mesa }} ({{ $factura->reserva->numero_personas }} personas)</td>
                                    <td class="text-end">${{ number_format($factura->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>IVA 19%</td>
                                    <td class="text-end">${{ number_format($factura->impuestos, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-end"><strong>${{ number_format($factura->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="border p-3 bg-light">
                            <h6>Información Adicional</h6>
                            <p class="mb-1 small">
                                <strong>Factura generada el:</strong> {{ $factura->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-1 small">
                                <strong>Reserva creada por:</strong> {{ $factura->reserva->usuario->name }}
                            </p>
                            <p class="mb-0 small text-muted">
                                Esta factura fue generada automáticamente por el sistema de reservas.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button onclick="window.print()" class="btn btn-outline-primary me-2">
                            <i class="fas fa-print me-2"></i>Imprimir Factura
                        </button>
                        <a href="{{ route('facturas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Facturas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .header-buttons, .btn {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection