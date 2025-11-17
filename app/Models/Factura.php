<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'numero_factura',
        'fecha_emision',
        'subtotal',
        'impuestos',
        'total',
        'estado'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function reserva() {
        return $this->belongsTo(Reserva::class);
    }

    public function generarNumeroFactura() {
        return 'FACT-' . date('Ymd') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
