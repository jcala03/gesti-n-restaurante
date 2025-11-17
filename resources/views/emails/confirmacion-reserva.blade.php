<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Reserva</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #343a40; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 20px; border-radius: 0 0 5px 5px; }
        .details { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #0d6efd; }
        .footer { text-align: center; margin-top: 20px; padding: 20px; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍽️ Restaurant Elegante</h1>
            <h2>Confirmación de Reserva</h2>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $cliente->nombre_completo }}</strong>,</p>
            <p>Tu reserva ha sido confirmada exitosamente. ¡Esperamos verte pronto!</p>
            
            <div class="details">
                <h3 style="color: #0d6efd; margin-top: 0;">📅 Detalles de tu Reserva</h3>
                <p><strong>Fecha:</strong> {{ $reserva->fecha_reserva->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $reserva->hora_reserva }}</p>
                <p><strong>Mesa:</strong> {{ $mesa->numero_mesa }} (Capacidad: {{ $mesa->capacidad }} personas)</p>
                <p><strong>Número de personas:</strong> {{ $reserva->numero_personas }}</p>
                <p><strong>Precio total:</strong> ${{ number_format($reserva->precio_total, 0, ',', '.') }} COP</p>
                @if($reserva->notas)
                <p><strong>Notas especiales:</strong> {{ $reserva->notas }}</p>
                @endif
            </div>
            
            <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <h4 style="margin-top: 0; color: #0a58ca;">💡 Recomendaciones</h4>
                <ul style="margin-bottom: 0;">
                    <li>Llega 10 minutos antes de tu reserva</li>
                    <li>Traer documento de identificación</li>
                    <li>Cancelación con 2 horas de anticipación</li>
                </ul>
            </div>
            
            <p>Si necesitas modificar o cancelar tu reserva, por favor contáctanos.</p>
        </div>
        
        <div class="footer">
            <p><strong>Restaurant Elegante</strong></p>
            <p>📍 Cra 45 #26-85, Medellín | 📞 (604) 444 1234</p>
            <p>✉️ info@restaurantelegante.com | 🌐 www.restaurantelegante.com</p>
            <p style="font-size: 12px; color: #999;">
                Este es un mensaje automático, por favor no respondas a este correo.
            </p>
        </div>
    </div>
</body>
</html>