<?php

namespace App\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ContextoController extends Controller
{
    public function edit(Request $request)
    {
        $profiles = DB::table('perfiles as p')->join('roles as r', 'r.id', '=', 'p.rol_id')
            ->join('periodos_academicos as a', 'a.id', '=', 'p.periodo_academico_id')
            ->where('p.usuario_id', $request->user()->id)->where('p.activo', true)->whereNull('p.deleted_at')
            ->orderByDesc('a.inicio')->get(['p.id', 'p.periodo_academico_id', 'r.nombre as rol', 'a.nombre as periodo', 'a.en_curso']);

        return Inertia::render('Auth/Contexto', ['perfiles' => $profiles]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(['perfil_id' => ['required', 'integer']]);
        $p = DB::table('perfiles')->where('id', $data['perfil_id'])->where('usuario_id', $request->user()->id)
            ->where('activo', true)->whereNull('deleted_at')->first();
        abort_unless($p, 403);
        $request->session()->put(['perfil_id' => $p->id, 'periodo_id' => $p->periodo_academico_id]);

        return redirect()->route('dashboard');
    }
}
