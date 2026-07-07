<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingItem extends Model
{
    protected $fillable = [
        'group',
        'icon',
        'title',
        'description',
        'value',
        'suffix',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'value' => 'integer',
        ];
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group)->where('is_active', true)->orderBy('sort_order');
    }
}
