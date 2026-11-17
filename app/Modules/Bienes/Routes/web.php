<?php

use App\Modules\Bienes\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('bienes')->name('bienes.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
