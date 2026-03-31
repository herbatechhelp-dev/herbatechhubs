<?php

namespace App\Livewire;

use App\Models\HubSetting;
use App\Models\Shortcut;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShortcutDashboard extends Component
{
    public string $search = '';

    public string $selectedCategory = 'All';

    #[Computed]
    public function categories(): array
    {
        return Shortcut::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->prepend('All')
            ->all();
    }

    #[Computed]
    public function shortcuts()
    {
        return Shortcut::query()
            ->where('is_active', true)
            ->when($this->search !== '', function ($query): void {
                $term = '%'.$this->search.'%';

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery
                        ->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('category', 'like', $term)
                        ->orWhere('url', 'like', $term);
                });
            })
            ->when($this->selectedCategory !== 'All', fn ($query) => $query->where('category', $this->selectedCategory))
                ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    #[Computed]
    public function hubSettings(): HubSetting
    {
        return HubSetting::current();
    }

    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function render(): View
    {
        $hubSettings = $this->hubSettings;

        return view('livewire.shortcut-dashboard')
            ->with('hubSettings', $hubSettings)
            ->layout('layouts.public', [
                'title' => $hubSettings->title,
                'metaTitle' => $hubSettings->title,
                'metaDescription' => $hubSettings->description,
                'faviconUrl' => $hubSettings->resolvedFaviconUrl(),
                'logoUrl' => $hubSettings->resolvedLogoUrl(),
            ]);
    }
}