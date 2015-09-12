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
## Authentication
Open up the config/app.php file and replace the AuthServiceProvider with:
```
Illuminate\Auth\AuthServiceProvider::class -> Kbwebs\MultiAuth\AuthServiceProvider::class
```
And open config/auth.php file and remove:
```PHP
'driver'  => 'eloquent'
'model'   => App\User::class,
'table'   => 'users'
```
and replace it with this array:
```PHP
'multi-auth' => [
    'admin' => [
        'driver' => 'eloquent',
        'model'  => App\Admin::class
    ],
    'user' => [
        'driver' => 'eloquent',
        'model'  => App\User::class
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
## Password Resets
Open up config/app.php file and replace the PasswordResetServiceProvider with:
```
Illuminate\Auth\Passwords\PasswordResetServiceProvider::class -> Kbwebs\MultiAuth\PasswordResets\PasswordResetServiceProvider::class
```
If you  want to use the password resets from this Package you will need to change this in each Model there use password resets:
```PHP
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
```
to
```PHP
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;
```
If you want to change the view for password reset for each auth type you can add this to the multi-auth array in config/auth.php:
```PHP
'email' => 'emails.users.password'
```
If you dont add this line, Laravel will automatically use the default path for emails.password like its defined in the password array.

To generate the password resets table you will need to run the following command:
```
php artisan kbwebs:multi-auth:create-resets-table
```
Likewise, if you want to clear all password resets, you have to run the following command:
```
php artisan kbwebs:multi-auth:clear-resets
```

**NOTE** It is very important that you replace the default service providers.
If you do not wish to use Password resets, then remove the original Password resets server provider as it will cause errors.

## Usage
#### Authentication:
It works just like the original laravel authentication library,
the only change is the **user()** or **admin()** it will match the auth type, as your defining in the multi-auth array:
```
Auth::attempt(['email' => $email, 'password' => $password], $remember)
```
But now it has to be like, with the **user()** or **admin()**:
```
Auth::user()->attempt(['email' => $email, 'password' => $password], $remember)
```
If you want to access the information for the authenticated user, you can do this:
```
Auth::user()->get();
```
OR
```
Auth::user()->get()->email
```
#### Password resets:
It works just like the original laravel authentication library,
the only change is the **user()** or **admin()** it will match the auth type, as your defining in the multi-auth array:
```
Password::sendResetLink($request->only('email'), function (Message $message) {
    $message->subject($this->getEmailSubject());
});
```
But now it has to be like, with the **user()** or **admin()**:
```
Password::user()->sendResetLink($request->only('email'), function (Message $message) {
    $message->subject($this->getEmailSubject());
});
```
Example for a password reset email:
```
Click here to reset your password: {{ URL::to('password/reset', array($type, $token)) }}.
```
This generates a URL like the following:
```
http://example.com/password/reset/user/21eb8ee5fe666r3b8d0521156bbf53266bnca572
```
Which will match the following route:
```
Route::get('password/reset/{type}/{token}', 'Controller@method');
```
#### Tip:
Remember to update all places where ex: Auth:: is been using, to ex: Auth::user() or what you have defined in config/auth.php