<?php

namespace Database\Seeders;

use App\Models\Shortcut;
use App\Services\FaviconFetcher;
use Illuminate\Database\Seeder;

class ShortcutSeeder extends Seeder
{
    public static function shortcuts(): array
    {
        return [
            [
                'sort_order' => 1,
                'title' => 'Laravel Documentation',
                'url' => 'https://laravel.com/docs',
                'description' => 'Referensi utama Laravel untuk routing, Eloquent, queue, dan deployment.',
                'category' => 'Development',
                'is_active' => true,
            ],
            [
                'sort_order' => 2,
                'title' => 'GitHub',
                'url' => 'https://github.com',
                'description' => 'Kelola repository, PR, issue, dan code review harian.',
                'category' => 'Development',
                'is_active' => true,
            ],
            [
                'sort_order' => 3,
                'title' => 'Notion Workspace',
                'url' => 'https://www.notion.so',
                'description' => 'Catatan kerja, planning sprint, dan dokumentasi pribadi.',
                'category' => 'Productivity',
                'is_active' => true,
            ],
            [
                'sort_order' => 4,
                'title' => 'Google Drive',
                'url' => 'https://drive.google.com',
                'description' => 'Akses file proyek, brief desain, dan dokumen penting.',
                'category' => 'Storage',
                'is_active' => true,
            ],
            [
                'sort_order' => 5,
                'title' => 'Dribbble',
                'url' => 'https://dribbble.com',
                'description' => 'Inspirasi UI/UX, motion, dan presentasi visual interface.',
                'category' => 'Design',
                'is_active' => true,
            ],
            [
                'sort_order' => 6,
                'title' => 'Figma',
                'url' => 'https://www.figma.com',
                'description' => 'Desain interface, prototyping, dan handoff visual produk.',
                'category' => 'Design',
                'is_active' => true,
            ],
            [
                'sort_order' => 7,
                'title' => 'MDN Web Docs',
                'url' => 'https://developer.mozilla.org',
                'description' => 'Referensi browser API, CSS, HTML, dan JavaScript modern.',
                'category' => 'Reference',
                'is_active' => true,
            ],
            [
                'sort_order' => 8,
                'title' => 'Toggl Track',
                'url' => 'https://track.toggl.com',
                'description' => 'Tracking waktu kerja untuk project, task, dan evaluasi fokus.',
                'category' => 'Productivity',
                'is_active' => false,
            ],
        ];
    }

    public function run(): void
    {
        $faviconFetcher = app(FaviconFetcher::class);

        collect(self::shortcuts())->each(function (array $shortcut) use ($faviconFetcher): void {
            Shortcut::query()->updateOrCreate(
                ['url' => $shortcut['url']],
                [
                    ...$shortcut,
                    'icon_path' => $faviconFetcher->preview($shortcut['url']) ?? $faviconFetcher->fallbackIcon($shortcut['url']),
                ],
            );
        });
    }
}