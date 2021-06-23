<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Movie extends Model
{
    protected $table = 'movie';
    public $timestamps = false;
    protected $fillable = ['title'];
    protected $appends = ['links'];
    
    public function cast()
    {
        return $this->hasMany(Cast::class);
    }
    public function getLinksAttribute($links): array
    {
        return [
            'self' => '/api/movie/' . $this->id,
            'cast' => '/api/cast/' . $this->id . '/cast'
        ];
    }
}
