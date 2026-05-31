<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'customer_id',
        'status',
        'reserved_at',
        'expires_at',
    ];

    public function items()
    {
        return $this->hasMany(ReservationItem::class);
    }
}
