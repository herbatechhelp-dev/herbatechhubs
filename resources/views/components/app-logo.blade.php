@props([
    'sidebar' => false,
])

@php($hubSettings = \App\Models\HubSetting::current())
@php($hasCustomBrandAsset = $hubSettings->hasUploadedLogo() || $hubSettings->hasUploadedFavicon() || filled($hubSettings->favicon_url))

@if($sidebar)
    <flux:sidebar.brand name="{{ $hubSettings->title }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            @if ($hasCustomBrandAsset)
                <img src="{{ $hubSettings->resolvedLogoUrl() }}" alt="{{ $hubSettings->title }} logo" class="size-5 object-contain" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="{{ $hubSettings->title }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            @if ($hasCustomBrandAsset)
                <img src="{{ $hubSettings->resolvedLogoUrl() }}" alt="{{ $hubSettings->title }} logo" class="size-5 object-contain" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
