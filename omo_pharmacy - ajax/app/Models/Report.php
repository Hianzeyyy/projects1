<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'generated_by',
        'type',
        'parameters',
        'file_path',
        'is_read_only',
        'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
