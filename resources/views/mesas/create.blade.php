@extends('layouts.app')

@section('page-title', 'Registrar Nueva Mesa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Información de la Mesa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('mesas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="numero_mesa" class="form-label">Número de Mesa *</label>
                        <input type="text" class="form-control @error('numero_mesa') is-invalid @enderror" 
                               id="numero_mesa" name="numero_mesa" 
                               value="{{ old('numero_mesa') }}" 
                               placeholder="Ej: M01, M02, VIP1..."
                               required>
                        @error('numero_mesa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                        <input type="number" class="form-control @error('capacidad') is-invalid @enderror" 
                               id="capacidad" name="capacidad" 
                               value="{{ old('capacidad', 4) }}" 
                               min="1" max="20" required>
                        @error('capacidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="precio_base" class="form-label">Precio Base (por persona) *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('precio_base') is-invalid @enderror" 
                                   id="precio_base" name="precio_base" 
                                   value="{{ old('precio_base', 25000) }}" 
                                   min="0" step="1000" required>
                        </div>
                        <div class="form-text">Precio en pesos colombianos por persona</div>
                        @error('precio_base')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('mesas.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Registrar Mesa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection