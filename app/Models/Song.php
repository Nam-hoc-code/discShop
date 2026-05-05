<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist_id',
        'cover_image',
        'cloud_url',
        'cloud_public_id',
        'genre_id',
        'status',
        'is_deleted',
    ];

    /**
     * Lấy thông tin nghệ sĩ sở hữu bài hát.
     */
    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    /**
     * Lấy thông tin thể loại nhạc.
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * Lấy danh sách các đĩa nhạc có chứa bài hát này.
     */
    public function discs()
    {
        return $this->belongsToMany(Disc::class);
    }

    /**
     * Scope lọc các bài hát đã được duyệt.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED')->where('is_deleted', false);
    }
}
