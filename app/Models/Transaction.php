<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_name',
        'type',
        'amount',
        'status',
        'reference',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
