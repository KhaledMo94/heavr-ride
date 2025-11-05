<?php

namespace App\Models;

use App\Services\SettingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GeneralSetting extends Model
{
    protected $fillable = [
        'setting_key','setting_value',
    ];

    public static function getSettings()
    {
        return self::all(['setting_key','setting_value']);
    }

    protected static function booted()
    {
        static::created(function (){
            Cache::driver('file')->forget('settings');
        });
        static::updated(function (){
            Cache::driver('file')->forget('settings');
        });
        static::deleted(function (){
            Cache::driver('file')->forget('settings');
        });
    }

}
