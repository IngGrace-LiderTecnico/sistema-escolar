<?php

namespace App\Modules\Jefatura\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('jefatura.ver'), 403);

        return Inertia::render('Jefatura/Index');
    }
}
