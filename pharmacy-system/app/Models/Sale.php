<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['medicine_id', 'medicine_name', 'quantity', 'unit_price', 'total_amount', 'customer_name'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
