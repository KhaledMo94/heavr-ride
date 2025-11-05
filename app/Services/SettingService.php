<?php

namespace App\Services;

use App\Interfaces\SettingInterface;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;

class SettingService implements SettingInterface
{
    public const CACHE_KEY = 'settings';

    public function get($key , $default = null)
    {
        $settings = Cache::driver('file')->get(self::CACHE_KEY);
        if($settings && isset($settings[$key])){
            return $settings[$key];
        }
        $settings = $this->all();
        return $settings[$key] ?? $default;
    }
    public function all()
    {
        return Cache::driver('file')->rememberForever(self::CACHE_KEY , function(){
            return GeneralSetting::getSettings();
        });
    }

    public function clear() :void
    {
        Cache::driver('file')->forget(self::CACHE_KEY);
    }
}
