<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'phone', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function songs()
    {
        return $this->hasMany(Song::class, 'artist_id');
    }

    public function discs()
    {
        return $this->hasMany(Disc::class, 'artist_id')->latest();
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteDiscs()
    {
        return $this->hasMany(FavoriteDisc::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Những ca sĩ họ đang theo dõi
    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'artist_id');
    }

    // Những người đang theo dõi họ
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'artist_id', 'user_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
