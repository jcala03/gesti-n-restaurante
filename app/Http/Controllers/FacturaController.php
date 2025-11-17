<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Reserva;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facturas = Factura::with('reserva.cliente')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('facturas.index', compact('facturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No usamos create porque las facturas se generan automáticamente
        return redirect()->route('facturas.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // No usamos store directo, se genera desde reservas
        return redirect()->route('facturas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        $factura->load('reserva.cliente', 'reserva.mesa');
        return view('facturas.show', compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // No editamos facturas directamente
        return redirect()->route('facturas.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // No actualizamos facturas directamente
        return redirect()->route('facturas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // No eliminamos facturas
        return redirect()->route('facturas.index');
    }

    /**
     * Métodos adicionales para nuestra lógica de negocio
     */
    
    public function marcarPagada(Factura $factura)
    {
        $factura->update(['estado' => 'pagada']);
        return redirect()->back()->with('success', 'Factura marcada como pagada.');
    }

    public function generarFactura(Reserva $reserva)
    {
        if (!$reserva->factura) {
            $factura = Factura::create([
                'reserva_id' => $reserva->id,
                'numero_factura' => 'FACT-' . date('Ymd') . '-' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT),
                'fecha_emision' => now(),
                'subtotal' => $reserva->precio_total,
                'impuestos' => round($reserva->precio_total * 0.19), // 19% IVA Colombia
                'total' => $reserva->precio_total + round($reserva->precio_total * 0.19),
                'estado' => 'pendiente'
            ]);
            
            return redirect()->route('facturas.show', $factura)
                ->with('success', 'Factura generada exitosamente.');
        }
        
        return redirect()->route('facturas.show', $reserva->factura);
    }
}