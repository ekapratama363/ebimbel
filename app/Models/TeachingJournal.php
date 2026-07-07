<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingJournal extends Model
{
    protected $fillable = [
        'date', 'kelompok_id', 'subject_id', 'tutor_id', 'material', 'notes',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }
}
