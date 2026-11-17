<?php

use App\Core\Http\Controllers\AccesoController;
use App\Core\Http\Controllers\ContextoController;
use App\Shared\Contracts\ContextoEscolar;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => redirect()->route('dashboard'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [AccesoController::class, 'create'])->name('login');
    Route::post('/login', [AccesoController::class, 'store'])->middleware('throttle:login');
});
Route::post('/logout', [AccesoController::class, 'destroy'])->middleware('auth')->name('logout');
Route::middleware(['auth', 'activo'])->group(function () {
    Route::get('/contexto', [ContextoController::class, 'edit'])->name('contexto.edit');
    Route::post('/contexto', [ContextoController::class, 'update'])->name('contexto.update');
    Route::get('/inicio', function (ContextoEscolar $contexto) {
        $modules = collect(config('modulos'))->filter(fn ($m) => $contexto->permite($m['slug'].'.ver'))->values();

        return Inertia::render('Dashboard', ['modulos' => $modules]);
    })->middleware('contexto')->name('dashboard');
});
