<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LandingSlide extends Model
{
    protected $fillable = [
        'tag', 'title', 'description', 'image_path', 'cta_text', 'cta_link',
        'slide_style', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }

    public function deleteImageFile(): void
    {
        if (! $this->image_path || ! str_starts_with($this->image_path, 'storage/')) {
            return;
        }

        Storage::disk('public')->delete(str_replace('storage/', '', $this->image_path));
    }
}
