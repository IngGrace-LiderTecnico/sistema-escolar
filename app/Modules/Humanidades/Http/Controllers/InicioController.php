<?php

namespace App\Modules\Humanidades\Http\Controllers;

use App\Shared\Contracts\ContextoEscolar;
use Inertia\Inertia;

class InicioController
{
    public function __invoke(ContextoEscolar $contexto)
    {
        abort_unless($contexto->permite('humanidades.ver'), 403);

        return Inertia::render('Humanidades/Index');
    }
}
