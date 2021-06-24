# 4 Api With Authentication

## 4.1 Create and protect your route with JWT algoritm

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

        
2. Get the library to use JWT authentication:

        composer require firebase/php-jwt


6. Enable the Eloquent and Facades  ./bootstrap/app.php uncomment this lines  
```php

$app->withFacades();
$app->withEloquent();


```


7.  Define the database configuration in this ./.env. If you use sqlite you have to create a empty file with this path /database/database.sqlite
    
        DB_CONNECTION=sqlite

8. Use the migration to create your authentication table: 

        php ./artisan make:migration create_table_users --create=users

9. Customize the migration: 

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
16. Execute php artisan migrate to create your tables.  

        php ./artisan migrate


16. Use the seeder to add a first user test:

        php ./artisan make:seeder UserSeeder


17. Customize seeder: 

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

18. Register your seeder in DatabaseSeeder.php and execute the seeder: 

```php    
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
    }
}
```
17. Execute the migrations to create your table: 

        php ./artisan make:seeder UserSeeder
        




4. Enable the middleware and register Auth Service Provider, in ./bootstrap/app.php uncomment this line  
```php

$app->routeMiddleware([
        'auth' => App\Http\Middleware\Authenticate::class,
]);

$app->register(App\Providers\AuthServiceProvider::class);

```


18. Customize the provider AuthServiceProvider.php boot method: 
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

5. Tell your route what middleware you want use: 

```php
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
        return "Anything Protected";
});
```
        Obs: If you wanna change the table name or any field you may mapping user class into ./app/Models/User.php. 




## 4.2 Define Your Own Authentication