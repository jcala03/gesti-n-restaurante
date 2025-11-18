<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::withCount('reservas')->orderBy('created_at', 'desc')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        logger('=== ANTES DE VALIDACIÓN ===');
    logger('Datos recibidos:', $request->all());
    
    try {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefono' => 'required|string|max:20'
        ]);

        logger('=== DESPUÉS DE VALIDACIÓN ===');
        logger('Datos validados:', $validated);

        // Crear con los datos validados
        $cliente = Cliente::create($validated);
        
        logger('=== CLIENTE CREADO ===');
        logger('Cliente ID: ' . $cliente->id);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado exitosamente.');
            
    } catch (\Exception $e) {
        logger('=== ERROR EN STORE ===');
        logger('Error: ' . $e->getMessage());
        logger('Datos en error: ', $request->all());
        
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
    }

    public function show(Cliente $cliente)
    {
        $historial = $cliente->historialReservas;
        return view('clientes.show', compact('cliente', 'historial'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

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

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}