<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function all(): array
    {
        return Setting::all()->pluck('value', 'key')->toArray();
    }

    public function updateMany(array $data): void
    {
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
