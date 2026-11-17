<?php

namespace App\Modules\Bienes\Providers;

use Illuminate\Support\ServiceProvider;

class BienesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
