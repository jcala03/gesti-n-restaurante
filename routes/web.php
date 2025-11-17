<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\FacturaController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);

    // Client Routes
    Route::resource('clientes', ClienteController::class);

    // Mesa Routes  
    Route::resource('mesas', MesaController::class);
    Route::get('mesas/disponibilidad', [MesaController::class, 'disponibilidad'])->name('mesas.disponibilidad');

    // Reservation Routes
    Route::resource('reservas', ReservaController::class);
    Route::post('reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::post('reservas/consultar-disponibilidad', [ReservaController::class, 'consultarDisponibilidad'])->name('reservas.consultar-disponibilidad');

    // Invoice Routes
    Route::resource('facturas', FacturaController::class)->only(['index', 'show']);
    Route::post('facturas/{factura}/marcar-pagada', [FacturaController::class, 'marcarPagada'])->name('facturas.marcar-pagada');
    Route::post('reservas/{reserva}/generar-factura', [FacturaController::class, 'generarFactura'])->name('reservas.generar-factura');
});