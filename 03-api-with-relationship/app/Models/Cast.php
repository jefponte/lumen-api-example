<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cast extends Model{
    protected $table = 'cast';
    public $timestamps = false;
    protected $fillable = ['name', 'movie_id'];
    protected $appends = ['links'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    public function getLinksAttribute($links): array
    {
        return [
            'self' => '/api/cast/' . $this->id,
            'movie' => '/api/movie/' . $this->movie_id
        ];
    }
}
