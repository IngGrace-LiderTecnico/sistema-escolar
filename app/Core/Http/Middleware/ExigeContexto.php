<?php

namespace App\Core\Http\Middleware;

use App\Shared\Contracts\ContextoEscolar;
use Closure;
use Illuminate\Http\Request;

class ExigeContexto
{
    public function __construct(private ContextoEscolar $contexto) {}

    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('perfil_id')) {
            return redirect()->route('contexto.edit');
        }
        $this->contexto->perfil();

        return $next($request);
    }
}
