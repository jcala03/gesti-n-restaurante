@extends('layouts.app')

@section('page-title', 'Editar Reserva')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Editar Reserva #{{ $reserva->id }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reservas.update', $reserva) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select class="form-select @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccionar Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ old('cliente_id', $reserva->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} - {{ $cliente->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero_personas" class="form-label">Número de Personas *</label>
                                <input type="number" class="form-control @error('numero_personas') is-invalid @enderror" 
                                       id="numero_personas" name="numero_personas" 
                                       value="{{ old('numero_personas', $reserva->numero_personas) }}" min="1" max="20" required>
                                @error('numero_personas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_reserva" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha_reserva') is-invalid @enderror" 
                                       id="fecha_reserva" name="fecha_reserva" 
                                       value="{{ old('fecha_reserva', $reserva->fecha_reserva->format('Y-m-d')) }}" required>
                                @error('fecha_reserva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hora_reserva" class="form-label">Hora *</label>
                                <input type="time" class="form-control @error('hora_reserva') is-invalid @enderror" 
                                       id="hora_reserva" name="hora_reserva" 
                                       value="{{ old('hora_reserva', $reserva->hora_reserva) }}" required>
                                @error('hora_reserva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mesa_id" class="form-label">Mesa *</label>
                        <select class="form-select @error('mesa_id') is-invalid @enderror" id="mesa_id" name="mesa_id" required>
                            <option value="">Seleccionar Mesa</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->id }}" 
                                    data-precio="{{ $mesa->precio_base }}"
                                    {{ old('mesa_id', $reserva->mesa_id) == $mesa->id ? 'selected' : '' }}>
                                    Mesa {{ $mesa->numero_mesa }} (Capacidad: {{ $mesa->capacidad }}, Precio: ${{ number_format($mesa->precio_base, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('mesa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                            <option value="confirmada" {{ old('estado', $reserva->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="cancelada" {{ old('estado', $reserva->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            <option value="completada" {{ old('estado', $reserva->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3">{{ old('notas', $reserva->notas) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Información Actual</h6>
                                <p><strong>Precio Actual:</strong> ${{ number_format($reserva->precio_total, 0, ',', '.') }}</p>
                                <p><strong>Factura:</strong> 
                                    @if($reserva->factura)
                                        {{ $reserva->factura->numero_factura }} - 
                                        <span class="badge bg-{{ $reserva->factura->estado == 'pagada' ? 'success' : 'warning' }}">
                                            {{ $reserva->factura->estado }}
                                        </span>
                                    @else
                                        <span class="text-muted">No generada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection