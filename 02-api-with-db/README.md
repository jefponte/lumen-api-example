## Api with database mapping

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

        
2. Enable the Eloquent, in ./bootstrap/app.php uncomment this line  
```php

        $app->withEloquent();
```


3.  Define the database configuration in this ./.env. If you use sqlite you have to create a empty file with this path /database/database.sqlite
    
        DB_CONNECTION=sqlite
        #DB_HOST=127.0.0.1
        #DB_PORT=3306
        #DB_DATABASE=homestead
        #DB_USERNAME=homestead
        #DB_PASSWORD=secret

4. With php artisan you can create your tables.

        php ./artisan make:migration create_table_movie --create=movie

        

5.  The migrations will be created in the folder ./database/migrations. Add the fields in the migration files, like this example: 
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

6. Execute php artisan migrate to create your tables.  

        php ./artisan migrate


7. Create the models to mapping the fields like this /app/Movie.php 

```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movie';
    public $timestamps = false;
    protected $fillable = ['title'];
}
```

8. Create your controller and add the method you will call in the route ./app/Http/Controllers/MovieController.php: 

```php
namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        return Movie::all();
        
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


9. Add the routes in this file ./routes/web.php: 

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

