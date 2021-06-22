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


4.  Define the database configuration in this ./.env. If you use sqlite you have to create a empty file with this path /database/database.sqlite
    
        DB_CONNECTION=sqlite
        #DB_HOST=127.0.0.1
        #DB_PORT=3306
        #DB_DATABASE=homestead
        #DB_USERNAME=homestead
        #DB_PASSWORD=secret


5. With php artisan you can create your tables.

        php ./artisan make:migration create_table_movie --create=movie
        php ./artisan make:migration create_table_credits --create=credits

6.  The migrations will be created in the folder ./database/migrations. Add the fields in the migration files, like this example: 
```php

class CreateTableMovie extends Migration
{
    /**
     * Run the migrations.
     *
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
     *
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

6. Create the models like this: 

        ./app/Movie.php 

7. Enable the Eloquent in ./bootstrap/app.php

        $app->withEloquent();

8. Run project with PHP and check out:  

        php -S localhost:8000 -t ./public

## 04 Api with relationship

Create relationship is to easy. 
## 05 Api With Authentication 

9. Use the migration to create your authentication table. 

        php ./artisan make:migration criar_tabela_app_user --create=app_user

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
3. Execute the migrations to create your table: 

        php ./artisan migrate


4. Get the library to use JWT authentication:

        composer require firebase/php-jwt

5. Enable the Route Midleware in /bootstrap/app.php

```php
$app->routeMiddleware([
        'auth' => App\Http\Middleware\Authenticate::class,
]);
```

6. Add the middleware add in the group rout you want to protect: 

```php
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
...
});
```

