<?php

namespace App\Providers;

use App\Core\Services\ContextoSesion;
use App\Core\Services\DirectorioCentral;
use App\Core\Services\FiltroEscolar;
use App\Infrastructure\Files\AlmacenNoConfigurado;
use App\Shared\Contracts\AlmacenArchivos;
use App\Shared\Contracts\ContextoEscolar;
use App\Shared\Contracts\DirectorioPersonas;
use App\Shared\Contracts\FiltraRegistros;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContextoEscolar::class, ContextoSesion::class);
        $this->app->bind(FiltraRegistros::class, FiltroEscolar::class);
        $this->app->bind(DirectorioPersonas::class, DirectorioCentral::class);
        $this->app->bind(AlmacenArchivos::class, AlmacenNoConfigurado::class);
    }

    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(5)->by($r->ip().'|'.$r->input('dni')));
    }
}
