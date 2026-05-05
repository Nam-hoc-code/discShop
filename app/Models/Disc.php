<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disc extends Model
{
    protected $fillable = ['title', 'artist_id', 'genre_id', 'price', 'description'];

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class)
            ->withPivot('position')
            ->orderByPivot('position', 'asc');
    }

    public function orders()
    {
        return $this->hasMany(DiscOrder::class);
    }
}
