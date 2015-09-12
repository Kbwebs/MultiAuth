<?php

namespace Kbwebs\MultiAuth\PasswordResets;

trait CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}