<?php

namespace App\Models;

use Database\Factories\ShortcutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}