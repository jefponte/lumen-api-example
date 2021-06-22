# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.


## 01 Simple API

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

2. You can create your router in this file: 

        ./routes/web.php 

3. Create your controller class like this: 

        ./app/Http/Controllers/SeriesController.php

3. Run project with PHP:  

        php -S localhost:8000 -t ./public

Your API is running in 

    http://localhost:8000/api/series

## 02 Api with database mapping


4.  Define the database configuration. If you use sqlite you have to create a empty file with this path /database/database.sqlite

        .env

5. With php artisan you can create your tables. 

        php ./artisan make:migration criar_tabela_series --create=series
        php ./artisan make:migration criar_tabela_episodios --create=episodios

6. Add the fields in the migration files, like this example: 
```php

class CriarTabelaSeries extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nome')->unique();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series');
    }
}

```

7. Execute php artisan migrate and the migrations will be created in the folder ./database/migrations.

        php ./artisan migrate


6. Create the models like 

        ./app/Serie.php 
        ./app/Episodios.php 

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


