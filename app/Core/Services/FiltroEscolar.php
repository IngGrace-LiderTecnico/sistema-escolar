<?php

namespace App\Core\Services;

use App\Shared\Contracts\ContextoEscolar;
use App\Shared\Contracts\FiltraRegistros;
use Illuminate\Database\Eloquent\Builder;

class FiltroEscolar implements FiltraRegistros
{
    public function __construct(private ContextoEscolar $contexto) {}

    public function aplicar(Builder $query, string $ambito, string $columnaDni = 'dni_docente'): Builder
    {
        $query->where($query->getModel()->qualifyColumn('periodo_academico_id'), $this->contexto->periodoId());
        $dnis = $this->contexto->docentesVisibles($ambito);
        if ($dnis !== null) {
            $query->whereIn($query->getModel()->qualifyColumn($columnaDni), $dnis);
        }

        return $query;
    }
}
