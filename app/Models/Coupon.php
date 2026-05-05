<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'expiry_date',
        'usage_limit',
        'used_count',
        'is_active'
    ];

    public static function findValid($code)
    {
        return self::where('code', $code)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->whereColumn('used_count', '<', 'usage_limit')
            ->first();
    }

    public function calculateDiscount($total)
    {
        if ($this->type === 'percent') {
            return ($total * $this->value) / 100;
        }
        return min($this->value, $total);
    }
}
