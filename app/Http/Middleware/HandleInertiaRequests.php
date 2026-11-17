<?php

namespace App\Http\Middleware;

use App\Shared\Contracts\ContextoEscolar;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [...parent::share($request),
            'auth' => ['dni' => $request->user()?->dni],
            'contexto' => function () use ($request) {
                if (! $request->user() || ! $request->session()->has('perfil_id') || $request->routeIs('contexto.*', 'login')) {
                    return null;
                }
                $c = app(ContextoEscolar::class);

                return ['periodoId' => $c->periodoId(), 'perfil' => $c->perfil()];
            },
        ];
    }
}
