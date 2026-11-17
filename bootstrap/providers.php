<?php

use App\Modules\Biblioteca\Providers\BibliotecaServiceProvider;
use App\Modules\Bienes\Providers\BienesServiceProvider;
use App\Modules\Coordinacion\Providers\CoordinacionServiceProvider;
use App\Modules\Humanidades\Providers\HumanidadesServiceProvider;
use App\Modules\Jefatura\Providers\JefaturaServiceProvider;
use App\Modules\Oficina\Providers\OficinaServiceProvider;
use App\Modules\Taller\Providers\TallerServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    OficinaServiceProvider::class,
    BibliotecaServiceProvider::class,
    BienesServiceProvider::class,
    HumanidadesServiceProvider::class,
    TallerServiceProvider::class,
    CoordinacionServiceProvider::class,
    JefaturaServiceProvider::class,
];
