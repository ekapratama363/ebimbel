<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutor extends Model
{
    protected $fillable = ['code', 'name', 'subject', 'experience_years', 'status', 'joined_at'];

    protected function casts(): array
    {
        return ['joined_at' => 'date'];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
