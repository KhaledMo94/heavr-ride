<?php

namespace App\Interfaces;

interface SettingInterface
{
    public function get($key , $default);
    public function all();
    public function clear();
}
