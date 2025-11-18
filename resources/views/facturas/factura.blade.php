<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->numero_factura }} - {{ config('app.name') }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0; 
            padding: 20px;
            background: #f8f9fa;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: #fff; 
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header { 
            border-bottom: 2px solid #dee2e6; 
            padding-bottom: 20px; 
            margin-bottom: 30px;
        }
        .section { 
            margin-bottom: 25px; 
        }
        .border-section { 
            border: 1px solid #dee2e6; 
            padding: 15px; 
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        .table th { 
            background-color: #f8f9fa; 
            font-weight: bold; 
        }
        .table-active { 
            background-color: #e9ecef !important; 
        }
        .text-end { 
            text-align: right; 
        }
        .badge { 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 0.8em; 
            color: white;
        }
        .bg-success { background: #28a745; }
        .bg-warning { background: #ffc107; color: #000; }
        .bg-danger { background: #dc3545; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-0 { margin-bottom: 0; }
        .mt-4 { margin-top: 2rem; }
        .text-muted { color: #6c757d; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado de la Factura -->
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h1 style="color: #2c3e50; margin-bottom: 10px;">FACTURA</h1>
                    <p class="mb-1"><strong>N°:</strong> {{ $factura->numero_factura }}</p>
                    <p class="mb-1"><strong>Fecha:</strong> {{ $factura->fecha_emision->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Estado:</strong> 
                        <span class="badge bg-{{ $factura->estado == 'pagada' ? 'success' : ($factura->estado == 'cancelada' ? 'danger' : 'warning') }}">
                            {{ ucfirst($factura->estado) }}
                        </span>
                    </p>
                </div>
                <div style="text-align: right;">
                    <h2 style="color: #2c3e50; margin-bottom: 10px;">{{ config('app.name', 'Restaurant Elegante') }}</h2>
                    <p class="mb-1">NIT: 900.123.456-7</p>
                    <p class="mb-1">Dirección: Cra 45 #26-85, Medellín</p>
                    <p class="mb-1">Teléfono: (604) 444 1234</p>
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="section">
            <h3 style="color: #2c3e50; border-bottom: 1px solid #dee2e6; padding-bottom: 8px;">Información del Cliente</h3>
            <div class="border-section">
                <p class="mb-1"><strong>Nombre:</strong> {{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $cliente->email }}</p>
                <p class="mb-1"><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
            </div>
        </div>

        <!-- Detalles de la Reserva -->
        <div class="section">
            <h3 style="color: #2c3e50; border-bottom: 1px solid #dee2e6; padding-bottom: 8px;">Detalles de la Reserva</h3>
            <div class="border-section">
                <p class="mb-1"><strong>Fecha Reserva:</strong> {{ $reserva->fecha_reserva->format('d/m/Y') }}</p>
                <p class="mb-1"><strong>Hora:</strong> {{ $reserva->hora_reserva }}</p>
                <p class="mb-1"><strong>Mesa:</strong> {{ $reserva->mesa->numero_mesa ?? 'N/A' }}</p>
                <p class="mb-1"><strong>N° Personas:</strong> {{ $reserva->numero_personas }}</p>
                @if($reserva->notas)
                <p class="mb-1"><strong>Notas:</strong> {{ $reserva->notas }}</p>
                @endif
            </div>
        </div>

        <!-- Desglose de Pagos -->
        <div class="section">
            <h3 style="color: #2c3e50; border-bottom: 1px solid #dee2e6; padding-bottom: 8px;">Desglose de Pagos</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Reserva Mesa {{ $reserva->mesa->numero_mesa ?? 'N/A' }} ({{ $reserva->numero_personas }} personas)</td>
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

        <!-- Información Adicional -->
        <div class="section mt-4">
            <div class="border-section" style="background: #f8f9fa;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">Información Adicional</h4>
                <p class="mb-1"><strong>Factura generada el:</strong> {{ $factura->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-1"><strong>Método de Pago:</strong> Pendiente</p>
                <p class="mb-0 text-muted">
                    Esta factura fue generada automáticamente por el sistema de reservas. 
                    Para cualquier consulta, por favor contacte con nuestro establecimiento.
                </p>
            </div>
        </div>

        <!-- Mensaje de Agradecimiento -->
        <div class="text-center mt-4" style="border-top: 1px solid #dee2e6; padding-top: 20px;">
            <p style="color: #6c757d; font-style: italic;">
                ¡Gracias por su reserva! Esperamos que disfrute de su experiencia en {{ config('app.name', 'nuestro restaurante') }}.
            </p>
        </div>
    </div>
</body>
</html>