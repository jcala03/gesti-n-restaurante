<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Mesa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'reservas_hoy' => Reserva::whereDate('fecha_reserva', today())->count(),
            'total_clientes' => Cliente::count(),
            'mesas_disponibles' => Mesa::where('disponible', true)->count(),
            'reservas_pendientes' => Reserva::where('estado', 'confirmada')
                ->whereDate('fecha_reserva', '>=', today())
                ->count()
        ];

        $reservas_hoy = Reserva::with(['cliente', 'mesa'])
            ->whereDate('fecha_reserva', today())
            ->orderBy('hora_reserva')
            ->get();

        return view('dashboard', compact('stats', 'reservas_hoy'));
    }
}

