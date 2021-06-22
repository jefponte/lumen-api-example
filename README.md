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

5. Run project with PHP int folder ./public and then you can see it running at this addres http://localhost:8000/api/movie

        php -S localhost:8000 -t ./public


## 02 Api with database mapping

6. Enable the Eloquent, in ./bootstrap/app.php uncomment this line  
```php

        $app->withEloquent();
```

7.  Define the database configuration in this ./.env. If you use sqlite you have to create a empty file with this path /database/database.sqlite
    
        DB_CONNECTION=sqlite
        #DB_HOST=127.0.0.1
        #DB_PORT=3306
        #DB_DATABASE=homestead
        #DB_USERNAME=homestead
        #DB_PASSWORD=secret

8. With php artisan you can create your tables.

        php ./artisan make:migration create_table_movie --create=movie

9.  The migrations will be created in the folder ./database/migrations. Add the fields in the migration files, like this example: 
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
10. Execute php artisan migrate to create your tables.  

        php ./artisan migrate

11. Create the models to mapping the fields like this: ./app/Movie.php 
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Movie extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];
}

```
12. Add the methods in your controller: ./app/Http/MovieController.php 

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


13. Add the routes in this file ./routes/web.php: 

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

14. Run project with PHP and check out:  

        php -S localhost:8000 -t ./public
