<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['module', 'action', 'name'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function getKeyAttribute(): string
    {
        return $this->module.'.'.$this->action;
    }
}
