<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inventory_id',
        'total_price',
        'payment_method',
        'transaction_type',
        'tax_amount',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
