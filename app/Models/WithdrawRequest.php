<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'image',
        'payed_at',
    ];

    protected $casts = [
        'amount'                =>'integer',
        'payed_at'              =>'datetime',
    ];

    protected $appends = [
        'image_url',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
