# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.

1. <a href="https://github.com/jefponte/lumen-api-example/tree/develop/01-simple-api#readme">Simple API</a>

2. <a href="https://github.com/jefponte/lumen-api-example/tree/develop/02-api-with-db#readme">Api with database mapping</a>

2. <a href="">Api with relationship</a>


## 05 Api With Authentication 


18. Get the library to use JWT authentication:

        composer require firebase/php-jwt

15. Use the migration to create your authentication table. 

        php ./artisan make:migration criar_tabela_app_user --create=app_user

16. Mapping user class use preexisting class into ./app/User.php or ./app/Models/User.php. 
Change the attribute table, fillable and hidden. 

```php
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table = 'app_user';
    protected $fillable = [
        'email',
    ];
    protected $hidden = [
        'password',
    ];
}
```
17. Execute the migrations to create your table: 

        php ./artisan migrate


19. Enable the Route Midleware in /bootstrap/app.php

```php
$app->routeMiddleware([
        'auth' => App\Http\Middleware\Authenticate::class,
]);
```

20. Add the middleware add in the group rout you want to protect: 

```php
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
...
});
```