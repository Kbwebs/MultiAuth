# MultiAuth For Laravel 5.1
---
## Installation
First, pull in the package through Composer.
```PHP
"require": {
    "kbwebs/multiauth": "dev-master"
}
```
Now you'll want to update or install via composer.
```
composer update
```
Next you open up config/app.php and replace the AuthServiceProvider and PasswordResetServiceProvider with:
```
AuthServiceProvider           -> Kbwebs\MultiAuth\AuthServiceProvider
PasswordResetServiceProvider  -> Kbwebs\MultiAuth\PasswordResets\PasswordResetServiceProvider
```
**NOTE** It is very important that you replace the default service providers. 
If you do not wish to use Password resets, then remove the original Password resets server provider as it will cause errors.
## Configuration
Take config/auth.php and remove:
```PHP
'driver'  => 'eloquent'
'model'   => App\User::class,
'table'   => 'users'
```
and replace it with:
```PHP
'multi-auth' => [
    'admin' => [
        'driver' => 'eloquent',
        'model'  => 'App\Admin'
    ],
    'user' => [
        'driver' => 'eloquent',
        'model'  => 'App\User'
    ]
]
```
If you want to use Database instead of Eloquent you can use it as:
```PHP
'user' => [
    'driver' => 'database',
    'table'  => 'users'
]
```
If you want to change the view for password reset for each auth you can add this to the array:
```PHP
'email' => 'emails.users.password'
```
If you don't add this it will use the default path emails.password like it's defined in password array.
## Password Resets
If you  want to use the password resets in this Package you will need to change this in each Model:
```PHP
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
```
to
```PHP
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;
```

