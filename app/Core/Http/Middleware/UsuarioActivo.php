<?php

namespace App\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioActivo
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->activo && DB::table('personas')->where('dni', $request->user()->dni)->whereNull('deleted_at')->exists(), 403);

        return $next($request);
    }
}
