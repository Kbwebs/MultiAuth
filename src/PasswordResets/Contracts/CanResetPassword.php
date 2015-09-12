<?php

namespace Kbwebs\MultiAuth\PasswordResets\Contracts;

interface CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     * @return string
     */
    public function getEmailForPasswordReset();
}