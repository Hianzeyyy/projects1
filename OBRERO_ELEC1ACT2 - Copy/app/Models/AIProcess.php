<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIProcess extends Model
{
    use HasFactory;

    protected $table = 'aiprocesses';

    protected $fillable = [
        'user_id',
        'form_type', // e.g., prediction, classification, etc.
        'input_data', // JSON or text
        'result', // JSON or text
        'status', // pending, completed, failed
    ];
}
