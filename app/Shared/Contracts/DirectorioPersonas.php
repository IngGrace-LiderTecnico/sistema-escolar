<?php

namespace App\Shared\Contracts;

interface DirectorioPersonas
{
    /** Includes inactive people for historical display; null if DNI never existed. */
    public function porDni(string $dni): ?array;
}
