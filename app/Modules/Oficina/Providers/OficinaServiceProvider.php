<?php

namespace App\Modules\Oficina\Providers;

use Illuminate\Support\ServiceProvider;

class OficinaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
