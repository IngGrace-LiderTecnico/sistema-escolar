<?php

namespace App\Modules\Jefatura\Providers;

use Illuminate\Support\ServiceProvider;

class JefaturaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
