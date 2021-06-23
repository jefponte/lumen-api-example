## 03. Api With Relationship



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
        php ./artisan make:migration create_table_cast --create=cast

        

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

```php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCast extends Migration
{
    public function up()
    {
        Schema::create('cast', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->integer('name');
            $table->integer('movie_id');
            $table->foreign('movie_id')
                ->references('movie')
                ->on('id');
        });
    }
    public function down()
    {
        Schema::dropIfExists('cast');
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


13. Create another table with migrate: 

        php ./artisan make:migration create_table_cast --create=cast

14. Customize your new migrate: 

```php
class CriarTabelaCast extends Migration
{
    public function up()
    {
        Schema::create('cast', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->integer('name');
            $table->integer('movie_id');
            $table->foreign('movie_id')
                ->references('movie')
                ->on('id');
        });
    }
    public function down()
    {
        Schema::dropIfExists('cast');
    }
}

```



15.  Execute php artisan migrate to create your tables. 


        php ./artisan migrate



16. Create the model with method to inform the relationship
```php

class Cast extends Model
{
    protected $table = 'cast';
    public $timestamps = false;
    protected $fillable = ['name', 'movie_id'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
```
17. A way to otimize code is create a base controller: 


```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseController
{
    protected $classe;

    public function index(Request $request)
    {
        return $this->classe::paginate($request->per_page);
    }

    public function store(Request $request)
    {
        return response()
            ->json(
                $this->classe::create($request->all()),
                201
            );
    }
    public function show(int $id)
    {
        $resource = $this->classe::find($id);
        if (is_null($resource)) {
            return response()->json('', 204);
        }

        return response()->json($resource);
    }

    public function update(int $id, Request $request)
    {
        $resource = $this->classe::find($id);
        if (is_null($resource)) {
            return response()->json([
                'erro' => 'Resource not found'
            ], 404);
        }
        $resource->fill($request->all());
        $resource->save();

        return $resource;
    }

    public function destroy(int $id)
    {
        $qtdRecursosRemovidos = $this->classe::destroy($id);
        if ($qtdRecursosRemovidos === 0) {
            return response()->json([
                'erro' => 'resource not found'
            ], 404);
        }

        return response()->json('', 204);
    }
}
```


```php

namespace App\Http\Controllers;

use App\Movie;

class MovieController extends BaseController
{
    public function __construct()
    {
        $this->classe = Movie::class;
    }
}

```


```php

namespace App\Http\Controllers;

use App\Cast;
use PhpParser\Node\Expr\Cast as ExprCast;

class CastController extends BaseController
{
    public function __construct()
    {
        $this->classe = Episodio::class;
    }
    public function fetchByMovie(int $movieId)
    {
        $cast = ExprCast::query()
            ->where('movie_id', $movieId)
            ->paginate();

        return $cast;
    }
}
```




Run project with PHP and check out:  

        php -S localhost:8000 -t ./public
