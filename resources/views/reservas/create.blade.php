@extends('layouts.app')

@section('page-title', 'Nueva Reserva')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Crear Nueva Reserva</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select class="form-select @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccionar Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
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
                                       value="{{ old('numero_personas', 2) }}" min="1" max="20" required>
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
                                       value="{{ old('fecha_reserva', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
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
                                       value="{{ old('hora_reserva', '19:00') }}" required>
                                @error('hora_reserva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mesa_id" class="form-label">Mesa *</label>
                        <select class="form-select @error('mesa_id') is-invalid @enderror" id="mesa_id" name="mesa_id" required>
                            <option value="">Primero verifique disponibilidad</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->id }}" 
                                    data-capacidad="{{ $mesa->capacidad }}"
                                    data-precio="{{ $mesa->precio_base }}"
                                    {{ old('mesa_id') == $mesa->id ? 'selected' : '' }}>
                                    Mesa {{ $mesa->numero_mesa }} (Capacidad: {{ $mesa->capacidad }}, Precio: ${{ number_format($mesa->precio_base, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="verificarDisponibilidad">
                                Verificar Disponibilidad
                            </button>
                            <span id="disponibilidadResult" class="ms-2"></span>
                        </div>
                        @error('mesa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3">{{ old('notas') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Resumen de la Reserva</h6>
                                <p id="resumenPrecio">Precio total: $0</p>
                                <small class="text-muted" id="resumenDetalles">
                                    Seleccione una mesa para ver el cálculo
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('reservas.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Crear Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_reserva');
    const horaInput = document.getElementById('hora_reserva');
    const personasInput = document.getElementById('numero_personas');
    const mesaSelect = document.getElementById('mesa_id');
    const verificarBtn = document.getElementById('verificarDisponibilidad');
    const resultadoSpan = document.getElementById('disponibilidadResult');
    const resumenPrecio = document.getElementById('resumenPrecio');
    const resumenDetalles = document.getElementById('resumenDetalles');
    const submitBtn = document.getElementById('submitBtn');

    function calcularPrecio() {
        const mesaOption = mesaSelect.options[mesaSelect.selectedIndex];
        if (mesaOption && mesaOption.value) {
            const precioBase = parseInt(mesaOption.getAttribute('data-precio'));
            const capacidad = parseInt(mesaOption.getAttribute('data-capacidad'));
            const personas = parseInt(personasInput.value) || 1;
            const total = precioBase * personas;
            
            resumenPrecio.textContent = `Precio total: $${total.toLocaleString()}`;
            resumenDetalles.innerHTML = `
                Mesa ${mesaOption.text.split(' ')[1]} | 
                ${personas} persona${personas > 1 ? 's' : ''} × $${precioBase.toLocaleString()} c/u
            `;
        } else {
            resumenPrecio.textContent = 'Precio total: $0';
            resumenDetalles.textContent = 'Seleccione una mesa para ver el cálculo';
        }
    }

    async function verificarDisponibilidad() {
        const fecha = fechaInput.value;
        const hora = horaInput.value;
        const personas = personasInput.value;

        if (!fecha || !hora || !personas) {
            resultadoSpan.innerHTML = '<span class="text-warning">Complete todos los campos</span>';
            return;
        }

        verificarBtn.disabled = true;
        verificarBtn.textContent = 'Verificando...';
        resultadoSpan.innerHTML = '<span class="text-info">Verificando disponibilidad...</span>';

        try {
            const response = await fetch('{{ route("reservas.consultar-disponibilidad") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    fecha: fecha,
                    hora: hora,
                    personas: personas
                })
            });

            const mesasDisponibles = await response.json();
            
            // Actualizar select de mesas
            mesaSelect.innerHTML = '<option value="">Seleccionar Mesa</option>';
            mesasDisponibles.forEach(mesa => {
                const option = document.createElement('option');
                option.value = mesa.id;
                option.textContent = `Mesa ${mesa.numero_mesa} (Capacidad: ${mesa.capacidad}, Precio: $${parseInt(mesa.precio_base).toLocaleString()})`;
                option.setAttribute('data-capacidad', mesa.capacidad);
                option.setAttribute('data-precio', mesa.precio_base);
                mesaSelect.appendChild(option);
            });

            if (mesasDisponibles.length > 0) {
                resultadoSpan.innerHTML = `<span class="text-success">${mesasDisponibles.length} mesas disponibles</span>`;
                submitBtn.disabled = false;
            } else {
                resultadoSpan.innerHTML = '<span class="text-danger">No hay mesas disponibles para esta fecha/hora</span>';
                submitBtn.disabled = true;
            }
        } catch (error) {
            resultadoSpan.innerHTML = '<span class="text-danger">Error al verificar disponibilidad</span>';
        } finally {
            verificarBtn.disabled = false;
            verificarBtn.textContent = 'Verificar Disponibilidad';
            calcularPrecio();
        }
    }

    // Event listeners
    verificarBtn.addEventListener('click', verificarDisponibilidad);
    mesaSelect.addEventListener('change', calcularPrecio);
    personasInput.addEventListener('input', calcularPrecio);

    // Verificar disponibilidad automáticamente cuando cambien los campos
    fechaInput.addEventListener('change', verificarDisponibilidad);
    horaInput.addEventListener('change', verificarDisponibilidad);
    personasInput.addEventListener('change', verificarDisponibilidad);

    // Verificar disponibilidad inicial si hay valores
    if (fechaInput.value && horaInput.value && personasInput.value) {
        verificarDisponibilidad();
    }
});
</script>
@endsection