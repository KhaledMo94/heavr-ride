<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Controllers\Controller;
use App\Traits\ActivityScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens, ActivityScopeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'phone_verified_at',
        'email_verified_at',
        'image',
        'status',
        'fcm_token',
        'player_id',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
        ];
    }
    //-----------------------------

    protected $appends = [
        'image_url',
        'is_phone_verified',
    ];

    //-----------------------------------------------

    protected static function booted()
    {
        static::created(function ($user){
            $user->wallet()->create();
        });

        static::deleting(function ($user) {

            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            if ($user->crane && $user->crane->image) {
                Storage::disk('public')->delete($user->crane->image);
            }

        });

    }


    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getIsPhoneVerifiedAttribute()
    {
        return is_null($this->phone_verified_at) ? false : true;
    }

    public function crane()
    {
        return $this->hasOne(Crane::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

}
