<?php

namespace Kbwebs\MultiAuth\PasswordResets;

use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;

interface TokenRepositoryInterface
{
    /**
     * Create a new token.
     * @param  \Kbwebs\MultiAuth\PasswordResets\CanResetPassword    $user
     * @param  string                                               $type
     * @return string
     */
    public function create(CanResetPasswordContract $user, $type);

    /**
     * Determine if a token record exists and is valid.
     * @param  \Kbwebs\MultiAuth\PasswordResets\CanResetPassword  $user
     * @param  string  $token
     * @param  string  $type
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token, $type);

    /**
     * Delete a token record.
     * @param  string  $token
     * @param  string  $type
     * @return void
     */
    public function delete($token, $type);

    /**
     * Delete expired tokens.
     * @return void
     */
    public function deleteExpired();
}