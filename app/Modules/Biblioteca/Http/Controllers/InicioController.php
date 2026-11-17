<?php

namespace App\Modules\Biblioteca\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('biblioteca.ver'), 403);

        return Inertia::render('Biblioteca/Index');
    }
}
