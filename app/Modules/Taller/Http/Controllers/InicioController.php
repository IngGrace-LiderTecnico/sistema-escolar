<?php

namespace App\Modules\Taller\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('taller.ver'), 403);

        return Inertia::render('Taller/Index');
    }
}
