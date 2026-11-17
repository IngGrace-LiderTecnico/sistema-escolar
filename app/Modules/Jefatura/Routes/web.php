<?php

use App\Modules\Jefatura\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'activo', 'contexto'])->prefix('jefatura')->name('jefatura.')->group(function () {
    Route::get('/', InicioController::class)->name('index');
});
