<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::withCount('reservas')->orderBy('created_at', 'desc')->get();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255', // CAMBIADO: nombre_completo → nombre
            'email' => 'required|email|unique:clientes,email',
            'telefono' => 'required|string|max:20'
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente) // CAMBIADO: string $id → Cliente $cliente
    {
        $historial = $cliente->historialReservas;
        return view('clientes.show', compact('cliente', 'historial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente) // CAMBIADO: string $id → Cliente $cliente
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente) 
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255', 
            
            'email' => 'required|email|unique:clientes,email,' . $cliente->id,
            'telefono' => 'required|string|max:20'
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente) // CAMBIADO: string $id → Cliente $cliente
    {
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}