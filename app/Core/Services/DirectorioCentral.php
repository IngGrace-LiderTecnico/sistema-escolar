<?php

namespace App\Core\Services;

use App\Shared\Contracts\DirectorioPersonas;
use Illuminate\Support\Facades\DB;

class DirectorioCentral implements DirectorioPersonas
{
    public function porDni(string $dni): ?array
    {
        $p = DB::table('personas')->where('dni', $dni)->first(['dni', 'nombres', 'apellidos', 'tipo', 'deleted_at']);

        return $p ? (array) $p : null;
    }
}
