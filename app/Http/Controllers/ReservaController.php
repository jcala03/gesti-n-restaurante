<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Mesa;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionReserva;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reservas = Reserva::with(['cliente', 'mesa', 'usuario'])
            ->orderBy('fecha_reserva', 'desc')
            ->orderBy('hora_reserva', 'desc')
            ->get();
            
        return view('reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clientes = Cliente::all();
        $mesas = Mesa::where('disponible', true)->get();
        return view('reservas.create', compact('clientes', 'mesas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'mesa_id' => 'required|exists:mesas,id',
            'fecha_reserva' => 'required|date|after_or_equal:today',
            'hora_reserva' => 'required',
            'numero_personas' => 'required|integer|min:1',
            'notas' => 'nullable|string'
        ]);

        // Verificar disponibilidad
        $mesa = Mesa::find($validated['mesa_id']);
        if (!$mesa->estaDisponible($validated['fecha_reserva'], $validated['hora_reserva'])) {
            return back()->withErrors(['mesa_id' => 'La mesa no está disponible para esa fecha y hora.']);
        }

        // Calcular precio total (ENTEROS para Colombia)
        $precio_total = $mesa->precio_base * $validated['numero_personas'];

        $reserva = Reserva::create($validated + [
            'user_id' => auth()->id(),
            'precio_total' => $precio_total
        ]);

        // Generar factura automáticamente
        $factura = Factura::create([
            'reserva_id' => $reserva->id,
            'numero_factura' => 'FACT-' . date('Ymd') . '-' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT),
            'fecha_emision' => now(),
            'subtotal' => $precio_total,
            'impuestos' => round($precio_total * 0.19), // 19% IVA Colombia
            'total' => $precio_total + round($precio_total * 0.19),
            'estado' => 'pendiente'
        ]);

        // Enviar email de confirmación (opcional)
        Mail::to($reserva->cliente->email)->send(new ConfirmacionReserva($reserva));

        return redirect()->route('reservas.show', $reserva)
            ->with('success', 'Reserva creada exitosamente. Factura generada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $reserva->load(['cliente', 'mesa', 'usuario', 'factura']);
        return view('reservas.show', compact('reserva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $clientes = Cliente::all();
        $mesas = Mesa::where('disponible', true)->get();
        return view('reservas.edit', compact('reserva', 'clientes', 'mesas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'mesa_id' => 'required|exists:mesas,id',
            'fecha_reserva' => 'required|date',
            'hora_reserva' => 'required',
            'numero_personas' => 'required|integer|min:1',
            'estado' => 'required|in:confirmada,cancelada,completada',
            'notas' => 'nullable|string'
        ]);

        // Recalcular precio si cambió la mesa o número de personas
        if ($validated['mesa_id'] != $reserva->mesa_id || $validated['numero_personas'] != $reserva->numero_personas) {
            $mesa = Mesa::find($validated['mesa_id']);
            $validated['precio_total'] = $mesa->precio_base * $validated['numero_personas'];
        }

        $reserva->update($validated);

        // Actualizar factura si existe
        if ($reserva->factura) {
            $reserva->factura->update([
                'subtotal' => $reserva->precio_total,
                'impuestos' => round($reserva->precio_total * 0.19),
                'total' => $reserva->precio_total + round($reserva->precio_total * 0.19)
            ]);
        }

        return redirect()->route('reservas.show', $reserva)
            ->with('success', 'Reserva actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $reserva->delete();
        return redirect()->route('reservas.index')
            ->with('success', 'Reserva eliminada exitosamente.');
    }

    public function cancelar(Reserva $reserva)
    {
        $reserva->cancelar();
        return redirect()->back()->with('success', 'Reserva cancelada exitosamente.');
    }

    public function consultarDisponibilidad(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'personas' => 'required|integer|min:1'
        ]);

        $mesasDisponibles = Mesa::mesasDisponibles(
            $validated['fecha'],
            $validated['hora'],
            $validated['personas']
        );

        return response()->json($mesasDisponibles);
    }
}
    

