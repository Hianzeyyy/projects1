<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory_items';

    protected $fillable = [
        'medicine_id',
        'medicine_name',
        'quantity',
        'batch_number',
        'expiry_date',
        'supplier_id',
        'supplier_name',
        'reorder_level'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
