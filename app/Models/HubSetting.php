<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HubSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'favicon_url',
        'favicon_path',
        'logo_path',
        'logo_zoom',
        'logo_position_x',
        'logo_position_y',
    ];

    public static function defaults(): array
    {
        return [
            'title' => 'Personal Shortcut Hub',
            'subtitle' => 'Integrated System',
            'description' => 'Cari tools, docs, dan website penting secara real-time. Setiap shortcut tampil dengan favicon otomatis, kategori, dan tampilan glassmorphism yang responsif.',
            'favicon_url' => null,
            'favicon_path' => null,
            'logo_path' => null,
            'logo_zoom' => 1,
            'logo_position_x' => 50,
            'logo_position_y' => 50,
        ];
    }

    public static function current(): self
    {
        return static::query()->first() ?? new static(static::defaults());
    }

    public function resolvedFaviconUrl(): string
    {
        if ($this->favicon_path) {
            return Storage::disk('public')->url($this->favicon_path);
        }

        return $this->favicon_url ?: '/favicon.ico';
    }

    public function resolvedLogoUrl(): string
    {
        if ($this->logo_path) {
            return Storage::disk('public')->url($this->logo_path);
        }

        return $this->resolvedFaviconUrl();
    }

    public function hasUploadedFavicon(): bool
    {
        return filled($this->favicon_path);
    }

    public function hasUploadedLogo(): bool
    {
        return filled($this->logo_path);
    }

    public function resolvedLogoZoom(): float
    {
        return max(1, min(2.5, (float) ($this->logo_zoom ?? 1)));
    }

    public function resolvedLogoPositionX(): int
    {
        return max(0, min(100, (int) ($this->logo_position_x ?? 50)));
    }

    public function resolvedLogoPositionY(): int
    {
        return max(0, min(100, (int) ($this->logo_position_y ?? 50)));
    }

    public function logoCropStyle(): string
    {
        return sprintf(
            'object-position: %d%% %d%%; transform: scale(%.2f);',
            $this->resolvedLogoPositionX(),
            $this->resolvedLogoPositionY(),
            $this->resolvedLogoZoom(),
        );
    }
}