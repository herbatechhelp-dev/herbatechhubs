<?php

namespace App\Models;

use Database\Factories\ShortcutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'url', 'description', 'category', 'icon_path', 'sort_order', 'is_active'])]
class Shortcut extends Model
{
    /** @use HasFactory<ShortcutFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function resolvedIconUrl(): ?string
    {
        if (! $this->icon_path) {
            return null;
        }

        if (str_starts_with($this->icon_path, 'http://') || str_starts_with($this->icon_path, 'https://')) {
            return $this->icon_path;
        }

        return Storage::disk('public')->url($this->icon_path);
    }

    public function hasCustomIcon(): bool
    {
        return $this->icon_path && !str_starts_with($this->icon_path, 'http://') && !str_starts_with($this->icon_path, 'https://');
    }
}