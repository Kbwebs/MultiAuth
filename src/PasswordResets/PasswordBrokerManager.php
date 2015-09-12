<?php

namespace Kbwebs\MultiAuth\PasswordResets;

use Illuminate\Contracts\Mail\Mailer as MailerContract;

class PasswordBrokerManager
{
	/**
	 * Holds all users passwordBroker instance
	 * @var array
	 */
	protected $brokers = [];

	public function __construct(TokenRepositoryInterface $tokens, MailerContract $mailer, $views, $providers)
	{
		if(!empty($providers)) {
			foreach($providers AS $type => $provider) {
				$this->brokers[$type] = new PasswordBroker($type, $tokens, $provider, $mailer, $views);
			}
		}
	}

	/**
	 * Here we are calling the broker matching what user its calling it
	 * @param string $name
	 * @param array  $arguments
	 */
	public function __call($name, $arguments = [])
	{
		if(array_key_exists($name, $this->brokers)) {
			return $this->brokers[$name];
		}
	}
}