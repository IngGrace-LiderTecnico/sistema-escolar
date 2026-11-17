<?php

namespace App\Shared\Contracts;

interface ContextoEscolar
{
    public function periodoId(): int;

    /** @return array{id:int,dni:string,nivel:string,rol:string,permisos:list<string>} */
    public function perfil(): array;

    public function permite(string $permiso): bool;

    /** null means unrestricted within this authorized scope; [] means no visible teachers. */
    public function docentesVisibles(string $ambito): ?array;
}
