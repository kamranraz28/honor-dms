<?php

namespace App\Repositories;

use App\Setting;
use Illuminate\Support\Facades\Cache;

class SettingRepository
{
    public function first()
    {
        return Cache::remember('app_settings', 86400, function () {
            return Setting::first();
        });
    }
}
