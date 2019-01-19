<?php

namespace Dirim\LvGenerateCrud;

use Illuminate\Support\ServiceProvider;
use Dirim\LvGenerateCrud\Commands\ControllerCrud\CreateControllerCrud;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // TODO boot
        // TODO
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateControllerCrud::class,
            ]);
        }
    }
}
