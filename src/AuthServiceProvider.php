<?php

namespace Kbwebs\MultiAuth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
     * Register bindings in the container.
     * @return void
     */
	public function register()
	{
		$this->app->singleton('auth', function ($app) {
			// Once the authentication service has actually been requested by the developer
			// we will set a variable in the application indicating such. This helps us
			// know that we need to set any queued cookies in the after event later.
			$app['auth.loaded'] = true;
			return new MultiManager($app);
		});
	}

	/**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return ['auth'];
    }
}