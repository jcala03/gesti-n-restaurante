@extends('layouts.app')

@section('page-title', 'Gestión de Mesas')

@section('header-buttons')
    <a href="{{ route('mesas.create') }}" class="btn btn-primary me-2">
        <i class="fas fa-plus me-2"></i>Nueva Mesa
    </a>
    <a href="{{ route('mesas.disponibilidad') }}" class="btn btn-info">
        <i class="fas fa-calendar-check me-2"></i>Ver Disponibilidad
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($mesas->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número Mesa</th>
                            <th>Capacidad</th>
                            <th>Precio Base</th>
                            <th>Estado</th>
                            <th>Reservas Activas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mesas as $mesa)
                        <tr>
                            <td>
                                <strong>{{ $mesa->numero_mesa }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $mesa->capacidad }} personas</span>
                            </td>
                            <td>
                                ${{ number_format($mesa->precio_base, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($mesa->disponible)
                                    <span class="badge bg-success">Disponible</span>
                                @else
                                    <span class="badge bg-danger">No Disponible</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $mesa->reservas->where('estado', 'confirmada')->count() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('mesas.edit', $mesa) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta mesa?')"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Total Mesas</h5>
                            <h3 class="text-primary">{{ $mesas->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Disponibles</h5>
                            <h3 class="text-success">{{ $mesas->where('disponible', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Capacidad Total</h5>
                            <h3 class="text-info">{{ $mesas->sum('capacidad') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Precio Promedio</h5>
                            <h3 class="text-warning">${{ number_format($mesas->avg('precio_base'), 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-chair fa-3x text-muted mb-3"></i>
                <h4>No hay mesas registradas</h4>
                <p class="text-muted">Comienza agregando tu primera mesa.</p>
                <a href="{{ route('mesas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Registrar Primera Mesa
                </a>
            </div>
        @endif
    </div>
</div>
@endsection