<?php

namespace Kbwebs\MultiAuth\PasswordResets;

use Illuminate\Support\ServiceProvider;
use Kbwebs\MultiAuth\PasswordResets\DatabaseTokenRepository as DbRepository;
use Kbwebs\MultiAuth\Console\PasswordResetsTableCommand;
use Kbwebs\MultiAuth\Console\ClearResetsTableCommand;

class PasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->registerPasswordBroker();
        $this->registerTokenRepository();
        $this->registerCommands();
    }

    /**
     * Register the password broker instance.
     * @return void
     */
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            // The password token repository is responsible for storing the email addresses
            // and password reset tokens. It will be used to verify the tokens are valid
            // for the given e-mail addresses. We will resolve an implementation here.
            $tokens     = $app['auth.password.tokens'];
            $providers  = [];
            $views      = [];
            if(!empty($app['config']['auth.multi-auth'])) {
                foreach($app['config']['auth.multi-auth'] AS $type => $config) {
                    $providers[$type] = $app['auth']->$type()->driver()->getProvider();
                    $views[$type]     = isset($config['email']) ? $config['email'] : $app['config']['auth.password.email'];
                }
            }
            // The password broker uses a token repository to validate tokens and send user
            // password e-mails, as well as validating that password reset process as an
            // aggregate service of sorts providing a convenient interface for resets.
            return new PasswordBrokerManager(
                $tokens, $app['mailer'], $views, $providers
            );
        });
    }

    /**
     * Register the token repository implementation.
     * @return void
     */
    protected function registerTokenRepository()
    {
        $this->app->singleton('auth.password.tokens', function ($app) {
            $connection = $app['db']->connection();
            // The database token repository is an implementation of the token repository
            // interface, and is responsible for the actual storing of auth tokens and
            // their e-mail addresses. We will inject this table and hash key to it.
            $table  = $app['config']['auth.password.table'];
            $key    = $app['config']['app.key'];
            $expire = $app['config']->get('auth.password.expire', 60);
            return new DbRepository($connection, $table, $key, $expire);
        });
    }

    /**
     * Register commands for console
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.multi-auth.resets', function($app) {
            return new PasswordResetsTableCommand($app['files']);
        });

        $this->app->singleton('command.multi-auth.resets.clear', function($app) {
            return new ClearResetsTableCommand();
        });

        $this->commands('command.multi-auth.resets', 'command.multi-auth.resets.clear');
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return ['auth.password', 'auth.password.tokens'];
    }
}