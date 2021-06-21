# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.


## 01 Simple API



        composer create-project --prefer-dist laravel/lumen 01-simples-api


You can create your router in this file: 

        routes/web.php 

Create your controller class like this: 

        app/Http/Controllers/SeriesController.php

Run project with PHP:  


        php -S localhost:8000 -t ./01-simples-api/public


Your API is running in 

    http://localhost:8000/api/series

## api with database mapping

With php artisan you can create your tables. 


        composer create-project --prefer-dist laravel/lumen 02-api-with-db

        php ./02-api-with-db/artisan make:migration criar_tabela_series --create=series
        php ./02-api-with-db/artisan make:migration criar_tabela_episodios --create=episodios

The migrations are created in the folder ./database/migrations and you have to customize it. 

- Change the database config in .env

- Create the models like ./app/Serie.php 

- Habilite Eloquent in ./ bootstrap/app.php


## api with authentication token

        composer create-project --prefer-dist laravel/lumen 03-api-authentication
        php ./03-api-authentication/artisan make:migration criar_tabela_series --create=series
        php ./03-api-authentication/artisan make:migration criar_tabela_episodios --create=episodios




        php ./03-api-authentication/artisan make:migration criar_tabela_episodios --create=usuarios

