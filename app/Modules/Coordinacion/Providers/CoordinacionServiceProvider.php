<?php

namespace App\Modules\Coordinacion\Providers;

use Illuminate\Support\ServiceProvider;

class CoordinacionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
