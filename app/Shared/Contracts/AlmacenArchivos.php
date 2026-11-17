<?php

namespace App\Shared\Contracts;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface AlmacenArchivos
{
    public function subir(string $rutaTemporal, string $nombre): string;

    public function descargar(string $driveFileId): StreamedResponse;

    public function eliminar(string $driveFileId): void;
}
