<?php

namespace App\Core\Services;

use App\Shared\Contracts\ContextoEscolar;
use Illuminate\Support\Facades\DB;

class ContextoSesion implements ContextoEscolar
{
    public function periodoId(): int
    {
        $id = session('periodo_id');
        if (! $id) {
            $id = DB::table('periodos_academicos')->where('en_curso', true)->value('id');
        }
        abort_unless($id && DB::table('periodos_academicos')->where('id', $id)->exists(), 409, 'No hay periodo académico disponible.');

        return (int) $id;
    }

    public function perfil(): array
    {
        $user = auth()->user();
        abort_unless($user && $user->activo && DB::table('personas')->where('dni', $user->dni)->whereNull('deleted_at')->exists(), 403);
        $p = DB::table('perfiles as p')->join('roles as r', 'r.id', '=', 'p.rol_id')
            ->where('p.id', session('perfil_id'))->where('p.usuario_id', $user->id)
            ->where('p.periodo_academico_id', $this->periodoId())->where('p.activo', true)
            ->whereNull('p.deleted_at')->select('p.id', 'p.rol_id', 'r.nombre', 'r.nivel')->first();
        abort_unless($p, 403, 'Selecciona un perfil válido para este periodo.');
        $permissions = DB::table('permiso_rol')->join('permisos', 'permisos.id', '=', 'permiso_rol.permiso_id')
            ->where('rol_id', $p->rol_id)->pluck('permisos.nombre')->all();

        return ['id' => $p->id, 'dni' => $user->dni, 'nivel' => $p->nivel, 'rol' => $p->nombre, 'permisos' => $permissions];
    }

    public function permite(string $permiso): bool
    {
        return in_array($permiso, $this->perfil()['permisos'], true);
    }

    public function docentesVisibles(string $ambito): ?array
    {
        $p = $this->perfil();

        return match ($p['nivel']) {
            'maximo' => null,
            'medio' => DB::table('coordinador_docente')->where('dni_coordinador', $p['dni'])
                ->where('periodo_academico_id', $this->periodoId())->where('ambito', $ambito)
                ->whereNull('deleted_at')->pluck('dni_docente')->all(),
            default => [$p['dni']],
        };
    }
}
