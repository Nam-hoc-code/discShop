<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteDisc extends Model
{
    protected $fillable = ['user_id', 'disc_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disc()
    {
        return $this->belongsTo(Disc::class);
    }
}
