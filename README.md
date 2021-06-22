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

6. Create the models to mapping the fields like this: ./app/Movie.php 
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Movie extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];
}

```
6. Add the methods in your controller: ./app/Http/MovieController.php 

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

7. Enable the Eloquent in ./bootstrap/app.php

        $app->withEloquent();
8. Add the ./routes/web.php: 

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

8. Run project with PHP and check out:  

        php -S localhost:8000 -t ./public

## 04 Api with relationship

Create relationship is to easy. First create a tables: 

        php ./artisan make:migration create_table_credits --create=credits
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