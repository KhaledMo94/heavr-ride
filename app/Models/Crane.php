<?php

namespace App\Models;

use App\Models\Scopes\DistanceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Crane extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'capacity',
        'license_plate',
        'image',
        'status',
        'is_online',
        'ratings_count',
        'avg_rating',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'capacity'  => 'integer',
        'ratings_count' => 'integer',
        'avg_rating' => 'float',
    ];

    protected $appends = [
        'image_url',
    ];

    protected static function booted()
    {
        // if(request()->has('latitude','longitude')){
        //     static::addGlobalScope('distance',new DistanceScope(request()->query('latitude') ,request()->query('longitude') ));
        // }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOnline(Builder $query)
    {
        return $query->where('is_online', true);
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->where('is_online', true);
    }

    public function scopeType(Builder $query, string $type)
    {
        return $query->where('type', $type);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

}
