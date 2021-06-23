<?php
namespace App\Http\Controllers;

use App\Models\Cast;

class CastController extends BaseController
{
    public function __construct()
    {
        $this->classe = Cast::class;
    }

    public function fetchByMovie(int $movieId)
    {
        $casts = Cast::query()
            ->where('movie_id', $movieId)
            ->paginate();

        return $casts;
    }
}
