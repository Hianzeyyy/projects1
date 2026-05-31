<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'manufacturer',
        'price',
        'description',
        'expiry_date',
        'stock',
        'supplier_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
        'stock' => 'integer',
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
