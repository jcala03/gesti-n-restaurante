<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Mesa;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaMail;
use App\Mail\ConfirmacionReserva;
use Illuminate\Support\Facades\DB;


class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with(['cliente', 'mesa', 'usuario'])
            ->orderBy('fecha_reserva', 'desc')
            ->orderBy('hora_reserva', 'desc')
            ->get();
            
        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $mesas = Mesa::where('disponible', true)->get();
        return view('reservas.create', compact('clientes', 'mesas'));
    }

    public function store(Request $request)
    {
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

    // Calcular precio total
    $precio_total = $mesa->precio_base * $validated['numero_personas'];

    // Usar transacción para asegurar consistencia
    \DB::beginTransaction();

    try {
        $reserva = Reserva::create($validated + [
            'user_id' => auth()->id(),
            'precio_total' => $precio_total,
            'estado' => 'confirmada'
        ]);

        // Generar factura automáticamente
        $factura = Factura::create([
            'reserva_id' => $reserva->id,
            'numero_factura' => 'FACT-' . date('Ymd') . '-' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT),
            'fecha_emision' => now(),
            'subtotal' => $precio_total,
            'impuestos' => round($precio_total * 0.19),
            'total' => $precio_total + round($precio_total * 0.19),
            'estado' => 'pendiente'
        ]);

        // Cargar relaciones para el email
        $factura->load('reserva.cliente', 'reserva.mesa');

        // ENVIAR EMAIL CON LA FACTURA - ¡ESTO ES LO QUE FALTABA!
        Mail::to($reserva->cliente->email)->send(new \App\Mail\FacturaMail($factura));

        \DB::commit();

        return redirect()->route('reservas.show', $reserva)
            ->with('success', 'Reserva creada exitosamente. Factura generada y enviada por correo.');

    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('Error creando reserva: ' . $e->getMessage());
        
        return back()->with('error', 'Hubo un error creando la reserva: ' . $e->getMessage());
    }
    }

    public function show(Reserva $reserva) // CORREGIDO: string $id → Reserva $reserva
    {
        $reserva->load(['cliente', 'mesa', 'usuario', 'factura']);
        return view('reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva) // CORREGIDO: string $id → Reserva $reserva
    {
        $clientes = Cliente::all();
        $mesas = Mesa::where('disponible', true)->get();
        return view('reservas.edit', compact('reserva', 'clientes', 'mesas'));
    }

    public function update(Request $request, Reserva $reserva) // CORREGIDO: string $id → Reserva $reserva
    {
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

    public function destroy(Reserva $reserva) // CORREGIDO: string $id → Reserva $reserva
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