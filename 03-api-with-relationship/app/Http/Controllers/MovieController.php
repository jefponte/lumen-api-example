<?php
namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends BaseController
{
    public function __construct()
    {
        $this->classe = Movie::class;
    }
}
