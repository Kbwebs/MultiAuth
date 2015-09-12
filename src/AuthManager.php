<?php

namespace Kbwebs\MultiAuth;

use Illuminate\Auth\AuthManager as OriginalAuthManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\DatabaseUserProvider;

class AuthManager extends OriginalAuthManager
{
    /**
     * Auth provider name
     * @var string
     */
    protected $name;

    /**
     * Auth configuration
     * @var array
     */
    protected $config;

    /**
     * Create a new manager instance.
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app, $name, $config)
    {
        parent::__construct($app);
        $this->config   = $config;
        $this->name     = $name;
    }

    /**
     * Call a custom driver creator.
     * @param  string  $driver
     * @return \Kbwebs\MultiAuth\Guard
     */
    protected function callCustomCreator($driver)
    {
        $custom = parent::callCustomCreator($driver);
        if($custom instanceof Guard) {
            return $custom;
        }
        return new Guard($custom, $this->app['session.store'], $this->name);
    }

    /**
     * Create an instance of the database driver.
     * @return \Kbwebs\MultiAuth\Guard
     */
    public function createDatabaseDriver()
    {
        $provider = $this->createDatabaseProvider();
        return new Guard($provider, $this->app['session.store'], $this->name);
    }

    /**
     * Create an instance of the database user provider.
     * @return \Illuminate\Auth\DatabaseUserProvider
     */
    protected function createDatabaseProvider()
    {
        $connection = $this->app['db']->connection();
        $table      = $this->config['table'];
        return new DatabaseUserProvider($connection, $this->app['hash'], $table);
    }

    /**
     * Create an instance of the Eloquent driver.
     * @return \Kbwebs\MultiAuth\Guard
     */
    public function createEloquentDriver()
    {
        $provider = $this->createEloquentProvider();
        return new Guard($provider, $this->app['session.store'], $this->name);
    }

    /**
     * Create an instance of the Eloquent user provider.
     * @return \Illuminate\Auth\EloquentUserProvider
     */
    protected function createEloquentProvider()
    {
        $model = $this->config['model'];
        return new EloquentUserProvider($this->app['hash'], $model);
    }

    /**
     * Get the default authentication driver name.
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config['driver'];
    }

    /**
     * Set the default authentication driver name.
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->config['driver'] = $name;
    }
}