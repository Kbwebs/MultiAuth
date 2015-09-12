<?php

namespace Kbwebs\MultiAuth;

use Illuminate\Foundation\Application;

class MultiManager
{
	/**
	 * Application
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Configuration
	 * @var array
	 */
	protected $config = [];

	/**
	 * Registered providers
	 * @var array
	 */
	protected $providers = [];

	/**
	 * Here we are collecting all the providers from config
	 * @param \Illuminate\Foundation\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app 		= $app;
		$this->config 	= $this->app['config']['auth.multi-auth'];
		if(!empty($this->config)) {
			foreach($this->config AS $key => $config) {
				$this->providers[$key] = new AuthManager($this->app, $key, $config);
			}
		}
	}

	/**
	 * Here we are calling the provider
	 * @param string $name
	 * @param array  $arguments
	 */
	public function __call($name, $arguments = [])
	{
		if(array_key_exists($name, $this->providers)) {
			return $this->providers[$name];
		} else {
			if(!empty($this->providers)) {
				foreach($this->providers AS $provider) {
					if($provider->$name() !== null) {
						return $provider->$name();
					}
				}
			}
		}
	}
}