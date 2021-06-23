
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
