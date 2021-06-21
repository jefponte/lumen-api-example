# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.


## 01 Simple API


<code>
composer create-project --prefer-dist laravel/lumen 01-simples-api
</code>

You can create your router in this file: 

        routes/web.php 

Create your controller class like this: 

        app/Http/Controllers/SeriesController.php

Run project with PHP:  

<code>
php -S localhost:8000 -t ./01-simples-api/public
</code>

Your API is running in 

    http://localhost:8000/api/series

## api with database mapping

With php artisan you can create your tables. 

<code>

composer create-project --prefer-dist laravel/lumen 02-api-with-db
php artisan make:migration criar_tabela_series --create=series
</code>


## api with authentication token

