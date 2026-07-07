<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = ['nik', 'name', 'position', 'department', 'status', 'joined_at'];

    protected function casts(): array
    {
        return ['joined_at' => 'date'];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
