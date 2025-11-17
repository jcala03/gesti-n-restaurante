<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesas = Mesa::all();
        return view('mesas.index', compact('mesas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mesas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_mesa' => 'required|string|unique:mesas',
            'capacidad' => 'required|integer|min:1',
            'precio_base' => 'required|integer|min:0'
        ]);

        Mesa::create($validated);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('mesas.show', compact('mesa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('mesas.edit', compact('mesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'numero_mesa' => 'required|string|unique:mesas,numero_mesa,' . $mesa->id,
            'capacidad' => 'required|integer|min:1',
            'precio_base' => 'required|integer|min:0',
            'disponible' => 'boolean'
        ]);

        $mesa->update($validated);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $mesa->delete();
        return redirect()->route('mesas.index')
            ->with('success', 'Mesa eliminada exitosamente.');
    }


    public function disponibilidad(Request $request)
    {
        $fecha = $request->get('fecha', today()->format('Y-m-d'));
        $mesas = Mesa::with(['reservas' => function($query) use ($fecha) {
            $query->where('fecha_reserva', $fecha)
                  ->where('estado', 'confirmada');
        }])->get();

        return view('mesas.disponibilidad', compact('mesas', 'fecha'));
    }
}
