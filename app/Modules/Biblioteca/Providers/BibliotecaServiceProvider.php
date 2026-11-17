<?php

namespace App\Modules\Biblioteca\Providers;

use Illuminate\Support\ServiceProvider;

class BibliotecaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
