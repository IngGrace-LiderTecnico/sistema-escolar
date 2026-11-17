<?php

use App\Modules\Taller\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('taller')->name('taller.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
