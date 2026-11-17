<?php

namespace App\Modules\Humanidades\Providers;

use Illuminate\Support\ServiceProvider;

class HumanidadesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
