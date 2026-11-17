<?php

use App\Modules\Coordinacion\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('coordinacion')->name('coordinacion.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
