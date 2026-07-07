<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'admin_brand',
        'landing_title',
        'meta_description',
        'logo_path',
        'organization_name',
    ];

    public static function current(): self
    {
        $id = Cache::remember('site_settings_id', 3600, function () {
            $setting = static::query()->first() ?? static::create(static::defaults());

            return $setting->id;
        });

        $setting = static::query()->find($id);

        if ($setting) {
            return $setting;
        }

        Cache::forget('site_settings_id');

        return static::query()->first() ?? static::create(static::defaults());
    }

    public static function defaults(): array
    {
        return [
            'site_name' => 'Jenius Kids',
            'admin_brand' => 'eBimbel',
            'landing_title' => 'Jenius Kids — Where Fun Meets Learning',
            'meta_description' => 'Jenius Kids — bimbingan belajar anak (BiMBA) yang menyenangkan. Program calistung, matematika, bahasa, dan pengembangan minat anak usia dini & sekolah dasar.',
            'logo_path' => 'assets/jenius-kids-logo.png',
            'organization_name' => 'Yayasan ADAYA Intelekta Bangsa',
        ];
    }

    public static function refreshCache(): void
    {
        Cache::forget('site_settings_id');
        Cache::forget('site_settings');
    }

    public function logoUrl(): string
    {
        return asset($this->logo_path);
    }
}
