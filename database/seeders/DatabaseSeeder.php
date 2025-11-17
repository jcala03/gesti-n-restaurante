<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Mesa;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Usuario dueño
        User::create([
            'name' => 'Yo',
            'email' => 'todos.son.cacorros.menos.yo@cuc.edu.co', 
            'password' => Hash::make('password'),
            'role' => 'owner'
        ]);


        User::create([
            'name' => 'Yo',
            'email' => 'todos.son.cacorros.menos.yo@cuc.edu.co', 
            'password' => Hash::make('password'),
            'role' => 'owner'
        ]);
        



        // Clientes de ejemplo - CAMBIA nombre_completo por nombre
        Cliente::create([
            'nombre' => 'Juan Pérez', // ← CAMBIADO
            'email' => 'juan@example.com',
            'telefono' => '1234567890'
        ]);

        Cliente::create([
            'nombre' => 'María García', // ← CAMBIADO
            'email' => 'maria@example.com',
            'telefono' => '0987654321'
        ]);

        // Mesas de ejemplo
        for ($i = 1; $i <= 10; $i++) {
            Mesa::create([
                'numero_mesa' => 'M' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacidad' => rand(2, 8),
                'precio_base' => rand(20000, 80000),
                'disponible' => true
            ]);
        }
    }
}