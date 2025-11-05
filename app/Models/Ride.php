<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'user_id',
        'crane_id',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'distance',
        'duration',
        'status',
        'fare',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'        => 'datetime',
        'completed_at'      => 'datetime',
        'fare'              =>'decimal:2',
        'distance'          =>'decimal:2',
        'duration'          =>'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crane()
    {
        return $this->belongsTo(Crane::class);
    }


}
