<?php

namespace App\Modules\Bienes\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('bienes.ver'), 403);

        return Inertia::render('Bienes/Index');
    }
}
