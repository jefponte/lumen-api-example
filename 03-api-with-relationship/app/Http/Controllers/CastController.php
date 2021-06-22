<?php

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