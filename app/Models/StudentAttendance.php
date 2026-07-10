<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttendance extends Model
{
    public const STATUSES = ['hadir', 'izin', 'sakit', 'alpha'];

    protected $fillable = ['date', 'kelompok_id', 'student_id', 'status', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => ucfirst($status),
        };
    }
}
