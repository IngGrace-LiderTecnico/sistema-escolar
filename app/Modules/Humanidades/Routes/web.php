<?php

use App\Modules\Humanidades\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('humanidades')->name('humanidades.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
