# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.


## 01 Simple API

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

3. Create your controller classes like this:  

        ./app/Http/Controllers/MovieController.php

4. Create the method you will call in the route:
```php
class MovieController extends Controller
{
     public function index(){
         return [
             "The Matrix",
             "Django"
         ];
     }
}
```
5. You can create your routes in this file: /routes/web.php calling the controller method: 
```php
$router->group(['prefix' => '/api'], function () use ($router) {
    $router->get('/movie', 'MovieController@index');
});
```

6. Run project with PHP int folder ./public and then you can see it running at this addres http://localhost:8000/api/movie

        php -S localhost:8000 -t ./public


## 02 Api with database mapping


4.  Define the database configuration. If you use sqlite you have to create a empty file with this path /database/database.sqlite

        .env

5. With php artisan you can create your tables.

        php ./artisan make:migration criar_tabela_series --create=movie
        php ./artisan make:migration criar_tabela_episodios --create=credits

6.  The migrations will be created in the folder ./database/migrations. Add the fields in the migration files, like this example: 
```php

class CriarTabelaSeries extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('movie', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie');
    }
}

```

7. Execute php artisan migrate to create your tables.  

        php ./artisan migrate

6. Create the models like 

        ./app/Movie.php 
        ./app/Credits.php 

7. Enable the Eloquent in ./bootstrap/app.php

        $app->withEloquent();

8. Run project with PHP:  

        php -S localhost:8000 -t ./public

## 03 Api With Authentication 

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen 03-api-authentication

2. Create your tables and the users table and add they fields in the migration. 

        php ./artisan make:migration criar_tabela_app_user --create=app_user

3. Customize your migration adding the fields and then execute the migrations: 

        php ./artisan migrate

3. Mapping user class use preexisting class into ./app/User.php or ./app/Models/User.php. 
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



2. Get the library to use JWT authentication:

        composer require firebase/php-jwt

2. Add the middleware in your rout file: ./routes/web.php 
```php
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

});
```
3. Enable the Route Midleware in /bootstrap/app.php

```php
$app->routeMiddleware([
        'auth' => App\Http\Middleware\Authenticate::class,
]);
```

        php ./03-api-authentication/artisan make:migration criar_tabela_usuarios --create=usuarios


