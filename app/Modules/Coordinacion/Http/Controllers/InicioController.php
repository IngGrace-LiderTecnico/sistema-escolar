<?php

namespace App\Modules\Coordinacion\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('coordinacion.ver'), 403);

        return Inertia::render('Coordinacion/Index');
    }
}
