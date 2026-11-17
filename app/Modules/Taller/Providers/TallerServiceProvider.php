<?php

namespace App\Modules\Taller\Providers;

use Illuminate\Support\ServiceProvider;

class TallerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
