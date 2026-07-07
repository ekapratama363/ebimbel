<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DailyReportMedia extends Model
{
    protected $fillable = [
        'daily_report_id',
        'type',
        'file_path',
        'original_name',
        'sort_order',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class, 'daily_report_id');
    }

    public function url(): string
    {
        return asset($this->file_path);
    }

    public function deleteFile(): void
    {
        if (! str_starts_with($this->file_path, 'storage/')) {
            return;
        }

        Storage::disk('public')->delete(str_replace('storage/', '', $this->file_path));
    }
}
