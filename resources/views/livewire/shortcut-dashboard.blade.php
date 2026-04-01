<div class="relative isolate overflow-hidden">
    <div class="pointer-events-none absolute inset-0 opacity-70">
        <div class="absolute left-1/2 top-24 h-64 w-64 -translate-x-1/2 rounded-full bg-sky-300/30 blur-3xl dark:bg-sky-500/20"></div>
        <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-amber-300/30 blur-3xl dark:bg-amber-500/20"></div>
    </div>

    <section class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8 lg:py-10">
        <header class="shortcut-glass-panel shortcut-fade-in mb-6 rounded-[2rem] p-5 shadow-2xl shadow-slate-900/10 backdrop-blur-xl sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex max-w-5xl flex-col gap-6 sm:flex-row sm:items-center sm:gap-6 lg:gap-5">
                    <div class="relative shrink-0">
                        <div class="absolute inset-0 rounded-[2.25rem] bg-sky-400/10 blur-2xl dark:bg-sky-500/12"></div>
                        <div class="relative flex h-36 w-36 items-center justify-center overflow-hidden rounded-[2.25rem] border border-white/40 bg-white/70 p-4 shadow-2xl shadow-slate-900/10 dark:border-white/10 dark:bg-white/8 sm:h-44 sm:w-44 sm:p-4 lg:h-[14.5rem] lg:w-[14.5rem] lg:p-4">
                            <img src="{{ $hubSettings->resolvedLogoUrl() }}" alt="{{ $hubSettings->title }} logo" class="max-h-full max-w-full rounded-[1.5rem] object-contain" style="{{ $hubSettings->logoCropStyle() }}">
                        </div>
                    </div>

                    <div class="space-y-4 lg:max-w-md">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/45 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">
                            <img src="{{ $hubSettings->resolvedFaviconUrl() }}" alt="{{ $hubSettings->title }} favicon" class="size-4 rounded-sm object-cover">
                            Dashboard Utama
                        </span>

                        <div class="space-y-1 lg:space-y-2">
                            <div class="flex flex-col items-start">
                                <h1 class="font-display text-5xl font-black uppercase tracking-tight text-slate-900 sm:text-6xl dark:text-white pb-1 border-b-2 border-slate-900 dark:border-white w-full max-w-max pr-1">
                                    {{ $hubSettings->title }}
                                </h1>
                                @if ($hubSettings->subtitle)
                                    <p class="text-2xl font-normal text-slate-700 sm:text-3xl dark:text-slate-300 mt-2">
                                        {{ $hubSettings->subtitle }}
                                    </p>
                                @endif
                            </div>
                            <p class="max-w-2xl text-xs leading-5 text-slate-500 sm:text-sm dark:text-slate-400 pt-2">
                                {{ $hubSettings->description }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600 dark:text-slate-300">
                    <button
                        type="button"
                        x-data
                        x-on:click="document.documentElement.classList.toggle('dark'); localStorage.setItem('shortcut-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 font-medium transition hover:-translate-y-0.5 hover:bg-white dark:border-white/10 dark:bg-white/6 dark:hover:bg-white/10"
                    >
                        <i data-lucide="moon-star" class="size-4"></i>
                        Toggle Theme
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 font-medium transition hover:-translate-y-0.5 hover:bg-white dark:border-white/10 dark:bg-white/6 dark:hover:bg-white/10">
                        <i data-lucide="settings-2" class="size-4"></i>
                        Admin Panel
                    </a>
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-300/60 bg-emerald-100/80 px-4 py-2 font-medium text-emerald-700 dark:border-emerald-500/25 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <i data-lucide="badge-check" class="size-4"></i>
                        {{ $this->shortcuts->count() }} active shortcuts
                    </span>
                </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                <label class="shortcut-input-shell flex items-center gap-3 rounded-2xl px-4 py-3">
                    <i data-lucide="search" class="size-5 text-slate-500 dark:text-slate-400"></i>
                    <input
                        type="text"
                        wire:model.live.debounce.250ms="search"
                        placeholder="Cari website, kategori, atau URL..."
                        class="w-full bg-transparent text-sm text-slate-800 outline-none placeholder:text-slate-500 dark:text-slate-100 dark:placeholder:text-slate-400"
                    >
                </label>

                <div class="flex flex-wrap gap-2">
                    @foreach ($this->categories as $category)
                        <button
                            type="button"
                            wire:click='setCategory(@json($category))'
                            class="rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] transition {{ $selectedCategory === $category ? 'border-sky-500 bg-sky-500 text-white shadow-lg shadow-sky-500/25' : 'border-white/45 bg-white/60 text-slate-700 hover:-translate-y-0.5 hover:bg-white dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10' }}"
                        >
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
            </div>
        </header>

        @if ($this->shortcuts->isEmpty())
            <div class="shortcut-glass-panel rounded-[2rem] p-10 text-center shadow-xl shadow-slate-900/10">
                <div class="mx-auto flex max-w-md flex-col items-center gap-4">
                    <div class="flex size-16 items-center justify-center rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                        <i data-lucide="search-x" class="size-7"></i>
                    </div>
                    <h2 class="font-display text-2xl font-semibold">Shortcut tidak ditemukan</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Coba kata kunci lain atau ganti filter kategori untuk melihat hasil yang tersedia.</p>
                </div>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($this->shortcuts as $shortcut)
                    <a
                        href="{{ $shortcut->url }}"
                        target="_blank"
                        rel="noreferrer"
                        class="shortcut-card shortcut-glass-panel group rounded-[1.75rem] p-5 shadow-xl shadow-slate-900/8 transition duration-300"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 items-center gap-4">
                                <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl border border-white/40 bg-white/75 shadow-lg shadow-slate-900/5 dark:border-white/10 dark:bg-white/10">
                                    @if ($shortcut->icon_path)
                                        @if ($shortcut->hasCustomIcon())
                                            <img src="{{ $shortcut->resolvedIconUrl() }}" alt="{{ $shortcut->title }} logo" class="h-full w-full rounded-2xl object-cover">
                                        @else
                                            <img src="{{ $shortcut->resolvedIconUrl() }}" alt="{{ $shortcut->title }} favicon" class="size-8 rounded-lg object-cover">
                                        @endif
                                    @else
                                        <i data-lucide="globe" class="size-7 text-slate-500 dark:text-slate-300"></i>
                                    @endif
                                </div>

                                <div class="min-w-0 space-y-1">
                                    <span class="inline-flex rounded-full border border-slate-300/70 bg-white/70 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">
                                        {{ $shortcut->category }}
                                    </span>
                                    <h2 class="truncate font-display text-xl font-semibold text-slate-900 dark:text-white">{{ $shortcut->title }}</h2>
                                </div>
                            </div>

                            <div class="rounded-full border border-slate-300/70 bg-white/60 p-2 text-slate-700 transition group-hover:-translate-y-1 group-hover:bg-slate-900 group-hover:text-white dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:group-hover:bg-white dark:group-hover:text-slate-900">
                                <i data-lucide="arrow-up-right" class="size-4"></i>
                            </div>
                        </div>

                        <p class="mt-5 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                            {{ $shortcut->description ?: 'Tidak ada deskripsi singkat untuk shortcut ini.' }}
                        </p>

                        <div class="mt-6 flex items-center justify-between gap-4 border-t border-slate-300/60 pt-4 text-xs text-slate-500 dark:border-white/10 dark:text-slate-400">
                            <span class="truncate">{{ parse_url($shortcut->url, PHP_URL_HOST) }}</span>
                            <span class="inline-flex items-center gap-1 font-semibold uppercase tracking-[0.2em] text-sky-600 dark:text-sky-300">
                                Open
                                <i data-lucide="external-link" class="size-3.5"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>