@extends('layouts.app')

@section('page-title', 'Disponibilidad de Mesas')

@section('header-buttons')
    <a href="{{ route('mesas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver a Mesas
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Consulta de Disponibilidad</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('mesas.disponibilidad') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" 
                       value="{{ $fecha }}" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Acción</label>
                <div>
                    <button type="submit" class="btn btn-primary">Consultar Disponibilidad</button>
                </div>
            </div>
        </form>

        <h6>Disponibilidad para: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h6>
        
        <div class="row">
            @foreach($mesas as $mesa)
            <div class="col-md-4 mb-3">
                <div class="card {{ $mesa->reservas->count() > 0 ? 'border-warning' : 'border-success' }}">
                    <div class="card-body">
                        <h5 class="card-title">Mesa {{ $mesa->numero_mesa }}</h5>
                        <p class="card-text">
                            <small class="text-muted">
                                Capacidad: {{ $mesa->capacidad }} personas<br>
                                Precio: ${{ number_format($mesa->precio_base, 0, ',', '.') }} por persona
                            </small>
                        </p>
                        
                        @if($mesa->reservas->count() > 0)
                            <div class="alert alert-warning py-2">
                                <small>
                                    <strong>Reservada:</strong><br>
                                    @foreach($mesa->reservas as $reserva)
                                        {{ $reserva->hora_reserva }} - {{ $reserva->cliente->nombre }}<br>
                                    @endforeach
                                </small>
                            </div>
                        @else
                            <div class="alert alert-success py-2">
                                <small><strong>Disponible todo el día</strong></small>
                            </div>
                        @endif
                        
                        <div class="d-grid">
                            <a href="{{ route('reservas.create') }}?mesa_id={{ $mesa->id }}&fecha={{ $fecha }}" 
                               class="btn btn-sm btn-outline-primary">
                                Reservar esta mesa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection