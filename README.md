# lumen-api-examples
Here you can learn how to make a API with lumen/laravel.


## 01 Simple API

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen 01-simples-api


2. You can create your router in this file: 

        ./01-simples-api/routes/web.php 

3. Create your controller class like this: 

        ./01-simples-api/app/Http/Controllers/SeriesController.php

3. Run project with PHP:  


        php -S localhost:8000 -t ./01-simples-api/public


Your API is running in 

    http://localhost:8000/api/series

## 02 Api with database mapping

1. Create laravel/lumen project: 

        composer create-project --prefer-dist laravel/lumen 02-api-with-db

2. You can create your router in this file: 

        ./01-simples-api/routes/web.php 


3.  Define the database configuration. If you use sqlite you have to create a empty file with this path 02-api-with-db/database/database.sqlite.

        ./02-api-with-db/.env

4. With php artisan you can create your tables. 

        php ./02-api-with-db/artisan make:migration criar_tabela_series --create=series
        php ./02-api-with-db/artisan make:migration criar_tabela_episodios --create=episodios

5. Execute php artisan migrate and the migrations will be created in the folder ./database/migrations.

        php ./02-api-with-db/artisan migrate


6. Create the models like 

        ./02-api-with-db/app/Serie.php 
        ./02-api-with-db/app/Episodios.php 

7. Enable the Eloquent in ./02-api-with-db/bootstrap/app.php

                $app->withEloquent();

8. Run project with PHP:  

        php -S localhost:8000 -t ./02-api-with-db/public

## 03 Api With Authentication 

        composer create-project --prefer-dist laravel/lumen 03-api-authentication
        php ./03-api-authentication/artisan make:migration criar_tabela_series --create=series
        php ./03-api-authentication/artisan make:migration criar_tabela_episodios --create=episodios

        php ./03-api-authentication/artisan make:migration criar_tabela_usuarios --create=usuarios

