<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'mesa_id', 
        'user_id',
        'fecha_reserva',
        'hora_reserva',
        'numero_personas',
        'estado',
        'precio_total',
        'notas'
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'precio_total' => 'decimal:2'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function mesa() {
        return $this->belongsTo(Mesa::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function factura() {
        return $this->hasOne(Factura::class);
    }

    public function cancelar() {
        $this->estado = 'cancelada';
        $this->save();
    }
}
