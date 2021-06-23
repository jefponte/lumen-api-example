## 01 Simple API

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen your-project-name

2. Create your controller and add the method you will call in the route:
        
        ./app/Http/Controllers/MovieController.php

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
3. Call the method you created in routes file: 


        ./routes/web.php 


```php
$router->group(['prefix' => '/api'], function () use ($router) {
    $router->get('/movie', 'MovieController@index');
});
```

Run project with PHP in folder ./public and then you can see it running at this addres http://localhost:8000/api/movie

        php -S localhost:8000 -t ./public
