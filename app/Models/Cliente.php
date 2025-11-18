<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email', 
        'telefono'
    ];

    public function reservas() {
        return $this->hasMany(Reserva::class);
    }

    public function getHistorialReservasAttribute() {
        return $this->reservas()->with('mesa')->orderBy('fecha_reserva', 'desc')->get();
    }
}