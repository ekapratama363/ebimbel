<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    protected $fillable = [
        'kelompok_id', 'nis', 'card_number', 'name', 'gender', 'birth_place', 'birth_date', 'religion',
        'address', 'photo_path', 'status', 'registered_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'registered_at' => 'date',
        ];
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function guardians(): HasMany
    {
        return $this->hasMany(Guardian::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function ensureCardNumber(): void
    {
        if ($this->card_number) {
            return;
        }

        $this->update([
            'card_number' => 'SID-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT),
        ]);
    }

    public function photoUrl(): ?string
    {
        return $this->photo_path ? asset($this->photo_path) : null;
    }

    public function deletePhotoFile(): void
    {
        if (! $this->photo_path || ! str_starts_with($this->photo_path, 'storage/')) {
            return;
        }

        Storage::disk('public')->delete(str_replace('storage/', '', $this->photo_path));
    }
}
