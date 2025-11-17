<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_mesa',
        'capacidad',
        'precio_base',
        'disponible'
    ];

    public function reservas() {
        return $this->hasMany(Reserva::class);
    }

    public function estaDisponible($fecha, $hora) {
        return !$this->reservas()
            ->where('fecha_reserva', $fecha)
            ->where('hora_reserva', $hora)
            ->where('estado', 'confirmada')
            ->exists();
    }

    public static function mesasDisponibles($fecha, $hora, $personas) {
        return self::where('disponible', true)
            ->where('capacidad', '>=', $personas)
            ->whereDoesntHave('reservas', function($query) use ($fecha, $hora) {
                $query->where('fecha_reserva', $fecha)
                      ->where('hora_reserva', $hora)
                      ->where('estado', 'confirmada');
            })
            ->get();
    }
}

