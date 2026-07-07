<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = [
        'section_key',
        'kicker',
        'title',
        'subtitle',
        'body',
        'body_secondary',
        'cta_primary_text',
        'cta_primary_link',
        'cta_secondary_text',
        'cta_secondary_link',
        'contact_email',
        'contact_phone',
        'extra_text',
    ];

    public static function map(): \Illuminate\Support\Collection
    {
        return static::query()->get()->keyBy('section_key');
    }
}
