@extends('layouts.app')

@section('page-title', 'Gestión de Facturas')

@section('content')
<div class="card">
    <div class="card-body">
        @if($facturas->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>N° Factura</th>
                            <th>Cliente</th>
                            <th>Fecha Emisión</th>
                            <th>Subtotal</th>
                            <th>Impuestos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                        <tr>
                            <td>
                                <strong>{{ $factura->numero_factura }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('clientes.show', $factura->reserva->cliente) }}" class="text-decoration-none">
                                    {{ $factura->reserva->cliente->nombre_completo }}
                                </a>
                            </td>
                            <td>{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                            <td>${{ number_format($factura->subtotal, 0, ',', '.') }}</td>
                            <td>${{ number_format($factura->impuestos, 0, ',', '.') }}</td>
                            <td>
                                <strong>${{ number_format($factura->total, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $factura->estado == 'pagada' ? 'success' : ($factura->estado == 'cancelada' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($factura->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($factura->estado == 'pendiente')
                                <form action="{{ route('facturas.marcar-pagada', $factura) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            onclick="return confirm('¿Marcar esta factura como pagada?')"
                                            title="Marcar como Pagada">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Estadísticas -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6>Total Facturas</h6>
                            <h4 class="text-primary">{{ $facturas->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6>Pagadas</h6>
                            <h4 class="text-success">{{ $facturas->where('estado', 'pagada')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6>Pendientes</h6>
                            <h4 class="text-warning">{{ $facturas->where('estado', 'pendiente')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6>Ingresos Totales</h6>
                            <h4 class="text-info">${{ number_format($facturas->sum('total'), 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <h4>No hay facturas registradas</h4>
                <p class="text-muted">Las facturas se generan automáticamente al crear reservas.</p>
                <a href="{{ route('reservas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Primera Reserva
                </a>
            </div>
        @endif
    </div>
</div>
@endsection