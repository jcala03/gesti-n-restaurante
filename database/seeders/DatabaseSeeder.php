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
        User::firstOrCreate(
            ['email' => 'admin@restaurant.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        // Usuario dueño - SOLO UNA VEZ
        User::firstOrCreate(
            ['email' => 'todos.son.cacorros.menos.yo@cuc.edu.co'],
            [
                'name' => 'Yo',
                'password' => Hash::make('password'),
                'role' => 'owner'
            ]
        );

        // Clientes de ejemplo
        Cliente::firstOrCreate(
            ['email' => 'juan@example.com'],
            [
                'nombre' => 'Juan Pérez',
                'telefono' => '1234567890'
            ]
        );

        Cliente::firstOrCreate(
            ['email' => 'maria@example.com'],
            [
                'nombre' => 'María García', 
                'telefono' => '0987654321'
            ]
        );

        // Mesas de ejemplo
        for ($i = 1; $i <= 10; $i++) {
            Mesa::firstOrCreate(
                ['numero_mesa' => 'M' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'capacidad' => rand(2, 8),
                    'precio_base' => rand(20000, 80000),
                    'disponible' => true
                ]
            );
        }
    }
}