<?php

namespace Appitized\Bedrock;

use Illuminate\Support\ServiceProvider;

class BedrockServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
          __DIR__.'/migrations/' => database_path('/migrations')
        ], 'migrations');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
