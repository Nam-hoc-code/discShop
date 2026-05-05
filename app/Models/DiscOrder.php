<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscOrder extends Model
{
    protected $fillable = [
        'disc_id',
        'user_id',
        'receiver_name',
        'phone',
        'address',
        'status',
        'shipping_fee',
        'coupon_code',
        'discount_amount',
        'total_amount'
    ];

    public function disc()
    {
        return $this->belongsTo(Disc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
