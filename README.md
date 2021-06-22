# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.


## 01 Simple API

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

2. Create your controller classes like this:  

        ./app/Http/Controllers/MovieController.php

3. Create the method you will call in the route:
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
4. Create your routes in this file: /routes/web.php calling the controller method: 
```php
$router->group(['prefix' => '/api'], function () use ($router) {
    $router->get('/movie', 'MovieController@index');
});
```

Run project with PHP int folder ./public and then you can see it running at this addres http://localhost:8000/api/movie

        php -S localhost:8000 -t ./public


## 02 Api with database mapping

5. Enable the Eloquent, in ./bootstrap/app.php uncomment this line  
```php

        $app->withEloquent();
```

6.  Define the database configuration in this ./.env. If you use sqlite you have to create a empty file with this path /database/database.sqlite
    
        DB_CONNECTION=sqlite
        #DB_HOST=127.0.0.1
        #DB_PORT=3306
        #DB_DATABASE=homestead
        #DB_USERNAME=homestead
        #DB_PASSWORD=secret

7. With php artisan you can create your tables.

        php ./artisan make:migration create_table_movie --create=movie

8.  The migrations will be created in the folder ./database/migrations. Add the fields in the migration files, like this example: 
```php

class CreateTableMovie extends Migration
{
    public function up()
    {
        Schema::create('movie', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('title');
        });
    }
    public function down()
    {
        Schema::dropIfExists('movie');
    }
}
```
9. Execute php artisan migrate to create your tables.  

        php ./artisan migrate

10. Create the models to mapping the fields like this: ./app/Movie.php 
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Movie extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];
}

```
11. Add the methods in your controller: ./app/Http/MovieController.php 

```php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        //return Movie::all(); -> fetch all without pagination
        return $this->classe::paginate($request->per_page);
    }
    public function store(Request $request)
    {
        return response()
            ->json(
                Movie::create($request->all()),
                201
            );
    } 
    public function show(int $id)
    {
        $movie = Movie::find($id);
        if (is_null($movie)) {
            return response()->json('', 204);
        }
        return response()->json($movie);
    }
    public function update(int $id, Request $request)
    {
        $movie = Movie::find($id);
        if (is_null($movie)) {
            return response()->json([
                'erro' => 'Resource not found'
            ], 404);
        }
        $movie->fill($request->all());
        $movie->save();
        return $movie;
    }
    public function destroy(int $id)
    {
        $qtdRecursosRemovidos = Movie::destroy($id);
        if ($qtdRecursosRemovidos === 0) {
            return response()->json([
                'erro' => 'Resource not found'
            ], 404);
        }
        return response()->json('', 204);
    }
}
```


12. Add the routes in this file ./routes/web.php: 

```php
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'movie'], function () use ($router) {
        $router->post('', 'MovieController@store');
        $router->get('', 'MovieController@index');
        $router->get('{id}', 'MovieController@show');
        $router->put('{id}', 'MovieController@update');
        $router->delete('{id}', 'MovieController@destroy');
    });
});

```

Run project with PHP and check out:  

        php -S localhost:8000 -t ./public

## 04 Api with relationship

Create relationship is to easy. First create a tables: 

        php ./artisan make:migration create_table_credits --create=credits
## 05 Api With Authentication 

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


18. Get the library to use JWT authentication:

        composer require firebase/php-jwt

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