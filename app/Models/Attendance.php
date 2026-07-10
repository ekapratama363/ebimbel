<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public const STATUSES = ['hadir', 'izin', 'cuti', 'tidak_hadir'];

    protected $fillable = ['employee_id', 'date', 'check_in', 'check_out', 'status', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
