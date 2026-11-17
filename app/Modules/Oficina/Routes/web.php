<?php

use App\Modules\Oficina\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('oficina')->name('oficina.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
