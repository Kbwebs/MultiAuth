<?php

namespace Kbwebs\MultiAuth;

use Illuminate\Auth\Guard as OriginalGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class Guard extends OriginalGuard
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $name;

    /**
     * Create a new authentication guard.
     * @param  \Illuminate\Contracts\Auth\UserProvider                      $provider
     * @param  \Symfony\Component\HttpFoundation\Session\SessionInterface   $session
     * @param  \Symfony\Component\HttpFoundation\Request                    $request
     * @return void
     */
    public function __construct(UserProvider $provider, SessionInterface $session, $name, Request $request = null)
    {
        parent::__construct($provider, $session, $request);
        $this->name = $name;
    }

    /**
     * Get a unique identifier for the auth session value.
     * @return string
     */
    public function getName()
    {
        return 'login_'.$this->name.'_'.md5(get_class($this));
    }

    /**
     * Get the name of the cookie used to store the "recaller".
     * @return string
     */
    public function getRecallerName()
    {
        return 'remember_'.$this->name.'_'.md5(get_class($this));
    }

    /**
     * Return the currently authenticated user.
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function get()
    {
        return $this->user();
    }
}