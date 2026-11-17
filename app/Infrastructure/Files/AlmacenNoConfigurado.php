<?php

namespace App\Infrastructure\Files;

use App\Shared\Contracts\AlmacenArchivos;
use LogicException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Fail closed until the institutional Drive adapter is configured. Never stores files locally. */
class AlmacenNoConfigurado implements AlmacenArchivos
{
    public function subir(string $rutaTemporal, string $nombre): string
    {
        throw new LogicException('Drive institucional pendiente de configurar.');
    }

    public function descargar(string $driveFileId): StreamedResponse
    {
        throw new LogicException('Drive institucional pendiente de configurar.');
    }

    public function eliminar(string $driveFileId): void
    {
        throw new LogicException('Drive institucional pendiente de configurar.');
    }
}
