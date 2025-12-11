<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'transaction_type',
        'transaction_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
