<?php

namespace App\Modules\Oficina\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('oficina.ver'), 403);

        return Inertia::render('Oficina/Index');
    }
}
