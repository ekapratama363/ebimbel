<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jenjang extends Model
{
    protected $fillable = ['code', 'name', 'sort_order', 'status'];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
