<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 31/08/2016
 * Time: 13:35
 */

namespace Ufox;

use Illuminate\Support\ServiceProvider;

class AuditoriaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'Auditoria', function() {

            return new LogAuditing();
        });
    }
}