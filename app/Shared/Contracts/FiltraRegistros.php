<?php

namespace App\Shared\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FiltraRegistros
{
    /** Apply period + active profile scope; permission is checked separately by Policy. */
    public function aplicar(Builder $query, string $ambito, string $columnaDni = 'dni_docente'): Builder;
}
