<?php

use App\Modules\Biblioteca\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('biblioteca')->name('biblioteca.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
