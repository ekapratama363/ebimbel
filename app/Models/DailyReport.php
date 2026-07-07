<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'date', 'kelompok_id', 'subject_id', 'tutor_id', 'summary', 'homework',
        'photo_count', 'video_count', 'status', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'published_at' => 'datetime',
        ];
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

    public function media(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DailyReportMedia::class)->orderBy('sort_order');
    }

    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->media()->where('type', 'photo');
    }

    public function videos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->media()->where('type', 'video');
    }

    public function syncMediaCounts(): void
    {
        $this->update([
            'photo_count' => $this->photos()->count(),
            'video_count' => $this->videos()->count(),
        ]);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
