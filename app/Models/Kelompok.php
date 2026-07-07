<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    protected $fillable = ['program_id', 'code', 'name', 'capacity', 'status'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function studentCount(): int
    {
        return $this->students()->where('status', 'aktif')->count();
    }
}
