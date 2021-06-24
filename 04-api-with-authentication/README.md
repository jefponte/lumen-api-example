# 4 Api With Authentication

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

        
2. Get the library to use JWT authentication:

        composer require firebase/php-jwt


3. Enable the Eloquent and Facades  ./bootstrap/app.php uncomment this lines  
```php

$app->withFacades();
$app->withEloquent();


```


4.  Define the database configuration in this ./.env. If you use sqlite you have to create a empty file with this path /database/database.sqlite
    
        DB_CONNECTION=sqlite

5. Use the migration to create your authentication table: 

        php ./artisan make:migration create_table_users --create=users

6. Customize the migration: 

```php
class CreateTableUsers extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('name');
            $table->string('password');
            $table->timestamps();
        });
    }        
}
```
7. Execute php artisan migrate to create your tables.  

        php ./artisan migrate


8. Use the seeder to add a first user test:

        php ./artisan make:seeder UserSeeder


9. Customize seeder: 

```php     
class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'email' => 'teste@email',
            'name' => 'test',
            'password' => Hash::make('password')
        ]);
    }
}
```

10. Register your seeder in DatabaseSeeder.php and execute the seeder: 

```php    
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
    }
}
```

11. Execute the migrations to create your table: 

        php ./artisan make:seeder UserSeeder
        


12. Enable the middleware and register Auth Service Provider, in ./bootstrap/app.php uncomment this line  
```php

$app->routeMiddleware([
        'auth' => App\Http\Middleware\Authenticate::class,
]);

$app->register(App\Providers\AuthServiceProvider::class);

```


13. Customize the provider AuthServiceProvider.php boot method: 
```php    
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function (Request $request) {
            if (!$request->hasHeader('Authorization')) {
                return null;
            }
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $dadosAutenticacao = JWT::decode($token, env('JWT_KEY'), ['HS256']);

            return User::where('email', $dadosAutenticacao->email)
                 ->first();
        });
    }
```
14. Create a class to generate a token 
```php

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $usuario = User::where('email', $request->email)->first();

        if (is_null($usuario)
            || !Hash::check($request->password, $usuario->password)
        ) {
            return response()->json('Invalid user', 401);
        }

        $token = JWT::encode(
            ['email' => $request->email],
            env('JWT_KEY')
        );

        return [
            'access_token' => $token
        ];
    }
}

```

15. Tell your route what middleware you want use and your route to make login: 

```php
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
        return "Anything Protected";
});

$router->post('/api/login', 'TokenController@generateToken');
```
        Obs: If you wanna change the table name or any field you may mapping user class into ./app/Models/User.php. 






