<?php

namespace Inventive\LaravelOwner;

use Illuminate\Support\ServiceProvider;

class OwnerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
	    $this->mergeConfigFrom(
	        __DIR__ . '/config/owner.php', 'owner'
	    );
		
        $this->publishes([
            __DIR__.'/../database/migrations/create_ownerstable.php' => database_path('migrations/'.date('Y_m_d_His').'_create_ownerstable.php'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
