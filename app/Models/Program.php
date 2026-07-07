<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = ['code', 'name', 'jenjang', 'status'];

    public function kelompoks(): HasMany
    {
        return $this->hasMany(Kelompok::class);
    }
}
