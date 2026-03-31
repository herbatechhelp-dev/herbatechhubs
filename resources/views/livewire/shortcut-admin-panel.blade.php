<div
    x-data="shortcutToastCenter()"
    x-on:shortcut-toast.window="push($event.detail)"
    class="relative flex h-full w-full flex-1 flex-col gap-6"
>
    <div class="pointer-events-none fixed right-4 top-4 z-50 flex w-full max-w-sm flex-col gap-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="toast.visible"
                x-transition.opacity.duration.250ms
                class="pointer-events-auto overflow-hidden rounded-2xl border border-white/10 bg-slate-950/90 p-4 text-white shadow-2xl shadow-slate-950/30 backdrop-blur"
            >
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 rounded-full p-2" :class="toast.type === 'success' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-sky-500/20 text-sky-300'">
                        <i data-lucide="bell-ring" class="size-4"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold" x-text="toast.type === 'success' ? 'Update berhasil' : 'Informasi'"></p>
                        <p class="mt-1 text-sm text-slate-300" x-text="toast.message"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <section class="shortcut-glass-panel rounded-[2rem] p-5 shadow-xl shadow-slate-900/10 sm:p-6">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
            <div class="max-w-2xl space-y-3">
                <div class="flex items-center gap-4">
                    <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-[1.5rem] border border-white/10 bg-white/80 shadow-lg shadow-slate-900/5 dark:bg-white/10">
                        <img src="{{ $dashboardLogoPreview }}" alt="{{ $dashboardTitle }} logo" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-2 rounded-full border border-sky-300/70 bg-sky-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700 dark:border-sky-500/20 dark:text-sky-300">
                            <i data-lucide="panel-top-open" class="size-4"></i>
                            {{ $dashboardTitle }} Admin
                        </span>
                        <h1 class="font-display text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Kelola semua shortcut untuk {{ $dashboardTitle }}.</h1>
                    </div>
                </div>
                <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Cari, filter, edit, dan atur posisi kartu shortcut dengan drag-and-drop. Branding logo serta nama ini mengikuti pengaturan yang Anda simpan di Dashboard Settings.</p>
            </div>

            <div class="flex flex-wrap gap-3 text-sm">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 font-medium text-slate-700 transition hover:-translate-y-0.5 hover:bg-white dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:bg-white/10">
                    <i data-lucide="house" class="size-4"></i>
                    View Hub
                </a>
                <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-slate-900 px-4 py-2 font-medium text-white transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white dark:text-slate-900">
                    <i data-lucide="plus" class="size-4"></i>
                    New Shortcut
                </button>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.5rem] border border-slate-300/60 bg-white/70 p-4 dark:border-white/10 dark:bg-white/5">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Total</p>
                <p class="mt-3 font-display text-3xl font-semibold text-slate-900 dark:text-white">{{ $this->stats['total'] }}</p>
            </div>
            <div class="rounded-[1.5rem] border border-emerald-300/60 bg-emerald-500/10 p-4 dark:border-emerald-500/20">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">Active</p>
                <p class="mt-3 font-display text-3xl font-semibold text-emerald-800 dark:text-emerald-200">{{ $this->stats['active'] }}</p>
            </div>
            <div class="rounded-[1.5rem] border border-amber-300/60 bg-amber-500/10 p-4 dark:border-amber-500/20">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-700 dark:text-amber-300">Hidden</p>
                <p class="mt-3 font-display text-3xl font-semibold text-amber-800 dark:text-amber-200">{{ $this->stats['hidden'] }}</p>
            </div>
            <div class="rounded-[1.5rem] border border-sky-300/60 bg-sky-500/10 p-4 dark:border-sky-500/20">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">Categories</p>
                <p class="mt-3 font-display text-3xl font-semibold text-sky-800 dark:text-sky-200">{{ $this->stats['categories'] }}</p>
            </div>
        </div>
    </section>

    <section class="shortcut-glass-panel rounded-[2rem] p-5 shadow-xl shadow-slate-900/10 sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl space-y-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-300/70 bg-amber-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-amber-700 dark:border-amber-500/20 dark:text-amber-300">
                    <i data-lucide="badge-info" class="size-4"></i>
                    Dashboard Settings
                </span>
                <h2 class="font-display text-2xl font-semibold text-slate-900 dark:text-white">Atur identitas dashboard publik.</h2>
                <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Ubah judul utama, deskripsi hero, dan favicon yang muncul di tab browser untuk halaman utama.</p>
            </div>

            <a href="{{ route('home') }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5 hover:bg-white dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:bg-white/10">
                <i data-lucide="monitor-up" class="size-4"></i>
                Preview Public Dashboard
            </a>
        </div>

        <form wire:submit="saveDashboardSettings" class="mt-6 grid gap-4 xl:grid-cols-[minmax(0,1fr)_220px] xl:items-start">
            <div class="space-y-4">
                <div>
                    <label for="dashboard-title" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Dashboard Title</label>
                    <input id="dashboard-title" type="text" wire:model.blur="dashboardTitle" class="shortcut-text-input" placeholder="Personal Shortcut Hub">
                    @error('dashboardTitle') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="dashboard-description" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Dashboard Description</label>
                    <textarea id="dashboard-description" wire:model.blur="dashboardDescription" rows="4" class="shortcut-text-input resize-none" placeholder="Deskripsi singkat dashboard publik"></textarea>
                    @error('dashboardDescription') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="dashboard-favicon-url" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Favicon URL</label>
                    <input id="dashboard-favicon-url" type="url" wire:model.live.debounce.300ms="dashboardFaviconUrl" class="shortcut-text-input" placeholder="https://example.com/favicon.png">
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Bisa pakai URL eksternal, atau timpa dengan upload file favicon di bawah.</p>
                    @error('dashboardFaviconUrl') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label for="dashboard-favicon-upload" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Upload Favicon</label>
                        <input id="dashboard-favicon-upload" type="file" wire:model="dashboardFaviconUpload" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white dark:text-slate-300 dark:file:bg-white dark:file:text-slate-900">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Gunakan file persegi 1:1 agar favicon tetap rapi di tab browser.</p>
                        @error('dashboardFaviconUpload') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        @if ($dashboardHasUploadedFavicon)
                            <button type="button" wire:click="removeDashboardFavicon" class="mt-3 inline-flex items-center gap-2 rounded-full border border-rose-300/70 bg-rose-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:-translate-y-0.5 dark:border-rose-500/20 dark:text-rose-300">
                                <i data-lucide="trash-2" class="size-3.5"></i>
                                Remove Uploaded Favicon
                            </button>
                        @endif
                    </div>

                    <div>
                        <label for="dashboard-logo-upload" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Upload Dashboard Logo</label>
                        <input id="dashboard-logo-upload" type="file" wire:model="dashboardLogoUpload" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white dark:text-slate-300 dark:file:bg-white dark:file:text-slate-900">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Pakai logo persegi atau mendekati persegi agar tetap proporsional di hero dashboard.</p>
                        @error('dashboardLogoUpload') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        @if ($dashboardHasUploadedLogo)
                            <button type="button" wire:click="removeDashboardLogo" class="mt-3 inline-flex items-center gap-2 rounded-full border border-rose-300/70 bg-rose-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:-translate-y-0.5 dark:border-rose-500/20 dark:text-rose-300">
                                <i data-lucide="trash-2" class="size-3.5"></i>
                                Remove Uploaded Logo
                            </button>
                        @endif
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-300/60 bg-white/80 p-4 dark:border-white/10 dark:bg-white/5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Logo Crop Tool</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Atur zoom dan titik fokus sebelum menyimpan supaya logo hero tampil pas.</p>
                        </div>
                        <button type="button" wire:click="resetLogoCrop" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">
                            <i data-lucide="refresh-cw" class="size-3.5"></i>
                            Reset Crop
                        </button>
                    </div>

                    <div class="mt-4 grid gap-4 lg:grid-cols-3">
                        <label>
                            <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                <span>Zoom</span>
                                <span>{{ number_format($dashboardLogoZoom, 2) }}x</span>
                            </div>
                            <input type="range" min="1" max="2.5" step="0.05" wire:model.live="dashboardLogoZoom" class="w-full accent-sky-500">
                        </label>

                        <label>
                            <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                <span>Focus X</span>
                                <span>{{ $dashboardLogoPositionX }}%</span>
                            </div>
                            <input type="range" min="0" max="100" step="1" wire:model.live="dashboardLogoPositionX" class="w-full accent-sky-500">
                        </label>

                        <label>
                            <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                <span>Focus Y</span>
                                <span>{{ $dashboardLogoPositionY }}%</span>
                            </div>
                            <input type="range" min="0" max="100" step="1" wire:model.live="dashboardLogoPositionY" class="w-full accent-sky-500">
                        </label>
                    </div>
                    @error('dashboardLogoZoom') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    @error('dashboardLogoPositionX') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    @error('dashboardLogoPositionY') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-900">
                        <i data-lucide="save" class="size-4"></i>
                        Save Dashboard Settings
                    </button>
                </div>
            </div>

            <aside class="rounded-[1.75rem] border border-slate-300/60 bg-white/70 p-5 dark:border-white/10 dark:bg-white/5">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Live Preview</p>
                <div class="mt-4 flex items-start gap-4">
                    <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-3xl border border-white/10 bg-white/85 shadow-lg shadow-slate-900/5 dark:bg-white/10">
                        <img src="{{ $dashboardFaviconPreview }}" alt="Dashboard favicon preview" class="max-h-8 max-w-8 rounded-xl object-contain">
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate font-display text-lg font-semibold text-slate-900 dark:text-white">{{ $dashboardTitle ?: 'Personal Shortcut Hub' }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $dashboardDescription ?: 'Deskripsi dashboard akan tampil di sini.' }}</p>
                    </div>
                </div>

                <div class="mt-5 rounded-[1.5rem] border border-slate-300/60 bg-white/80 p-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Hero Logo Preview</p>
                    <div class="mt-4 flex items-center justify-center rounded-[1.5rem] bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.20),_transparent_52%),linear-gradient(180deg,_rgba(255,255,255,0.95),_rgba(241,245,249,0.9))] p-6 dark:bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.15),_transparent_52%),linear-gradient(180deg,_rgba(15,23,42,0.92),_rgba(15,23,42,0.76))]">
                        <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-[1.75rem] shadow-2xl shadow-slate-900/10 sm:h-28 sm:w-28">
                            <img src="{{ $dashboardLogoPreview }}" alt="Dashboard logo preview" class="max-h-full max-w-full origin-center object-contain" style="object-position: {{ $dashboardLogoPositionX }}% {{ $dashboardLogoPositionY }}%; transform: scale({{ number_format($dashboardLogoZoom, 2, '.', '') }});">
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-[1.5rem] border border-dashed border-slate-300/70 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Brand Asset Guide</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <p>Favicon wajib rasio 1:1 dengan ukuran minimal 64 x 64 px agar tajam di tab browser.</p>
                        <p>Logo hero paling aman memakai rasio 1:1 atau mendekati persegi agar valid dan tetap proporsional saat dikecilkan.</p>
                        <p>Gunakan file transparan PNG atau SVG jika ingin logo menyatu rapi dengan efek glassmorphism.</p>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-slate-300/60 bg-white/80 p-3 text-center dark:border-white/10 dark:bg-white/5">
                            <div class="mx-auto flex aspect-square max-w-[92px] items-center justify-center rounded-xl border border-dashed border-slate-300/70 dark:border-white/10">
                                <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">1:1</span>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Favicon / logo compact</p>
                        </div>
                        <div class="rounded-2xl border border-slate-300/60 bg-white/80 p-3 text-center dark:border-white/10 dark:bg-white/5">
                            <div class="mx-auto flex aspect-[4/3] max-w-[124px] items-center justify-center rounded-xl border border-dashed border-slate-300/70 dark:border-white/10">
                                <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">4:3</span>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Logo hero lebih lega</p>
                        </div>
                    </div>
                </div>
            </aside>
        </form>
    </section>

    @if ($this->canManageUsers)
        <section class="shortcut-glass-panel rounded-[2rem] p-5 shadow-xl shadow-slate-900/10 sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl space-y-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-300/70 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700 dark:border-emerald-500/20 dark:text-emerald-300">
                        <i data-lucide="users-round" class="size-4"></i>
                        Akun Pengelola
                    </span>
                    <h2 class="font-display text-2xl font-semibold text-slate-900 dark:text-white">{{ $editingManagedUserId ? 'Edit akun admin atau pengelola.' : 'Buat akun admin atau pengelola baru.' }}</h2>
                    <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Akun pengelola bisa login ke admin panel untuk mengelola shortcut. Pembuatan, perubahan, dan penghapusan akun hanya tersedia untuk admin utama.</p>
                </div>
            </div>

            <div class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_320px] xl:items-start">
                <form wire:submit="saveManagedUser" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="managed-user-name" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nama</label>
                            <input id="managed-user-name" type="text" wire:model.blur="managedUserName" class="shortcut-text-input" placeholder="Nama pengelola">
                            @error('managedUserName') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="managed-user-email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Email</label>
                            <input id="managed-user-email" type="email" wire:model.blur="managedUserEmail" class="shortcut-text-input" placeholder="pengelola@domain.com">
                            @error('managedUserEmail') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="managed-user-password" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Password</label>
                            <input id="managed-user-password" type="password" wire:model.blur="managedUserPassword" class="shortcut-text-input" placeholder="{{ $editingManagedUserId ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $editingManagedUserId ? 'Isi hanya jika password perlu diganti.' : 'Gunakan minimal 8 karakter.' }}</p>
                            @error('managedUserPassword') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="managed-user-role" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Role</label>
                            <select id="managed-user-role" wire:model="managedUserRole" class="shortcut-text-input">
                                <option value="manager">Pengelola</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('managedUserRole') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3 pt-2">
                        <button type="button" wire:click="resetManagedUserForm" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                            <i data-lucide="rotate-ccw" class="size-4"></i>
                            {{ $editingManagedUserId ? 'Batal Edit' : 'Reset' }}
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-900">
                            <i data-lucide="{{ $editingManagedUserId ? 'save' : 'user-round-plus' }}" class="size-4"></i>
                            {{ $editingManagedUserId ? 'Simpan Perubahan' : 'Buat Akun' }}
                        </button>
                    </div>
                </form>

                <aside class="rounded-[1.75rem] border border-slate-300/60 bg-white/70 p-5 dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Daftar Admin & Pengelola</p>
                    <div class="mt-4 space-y-3">
                        @foreach ($this->manageableUsers as $managedUser)
                            <div class="rounded-2xl border border-slate-300/60 bg-white/80 p-3 dark:border-white/10 dark:bg-white/5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $managedUser->name }}@if($managedUser->is(auth()->user())) <span class="text-xs font-medium text-slate-500 dark:text-slate-400">(Anda)</span>@endif</p>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $managedUser->email }}</p>
                                    </div>
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] {{ $managedUser->role === 'admin' ? 'bg-sky-500/12 text-sky-700 dark:text-sky-300' : ($managedUser->role === 'manager' ? 'bg-emerald-500/12 text-emerald-700 dark:text-emerald-300' : 'bg-slate-500/12 text-slate-700 dark:text-slate-300') }}">
                                        {{ $managedUser->roleLabel() }}
                                    </span>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button type="button" wire:click="editManagedUser({{ $managedUser->id }})" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                        <i data-lucide="pencil-line" class="size-3.5"></i>
                                        Edit
                                    </button>
                                    <button type="button" wire:click="confirmManagedUserDelete({{ $managedUser->id }})" class="inline-flex items-center gap-2 rounded-full border border-rose-300/70 bg-rose-500/10 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:-translate-y-0.5 dark:border-rose-500/20 dark:text-rose-300">
                                        <i data-lucide="trash-2" class="size-3.5"></i>
                                        Hapus
                                    </button>
                                </div>

                                @if ($deleteManagedUserId === $managedUser->id)
                                    <div class="mt-3 rounded-2xl border border-rose-300/70 bg-rose-500/10 p-3 dark:border-rose-500/20">
                                        <p class="text-sm font-medium text-rose-800 dark:text-rose-200">Hapus {{ $deleteManagedUserName }} dari akun manajemen?</p>
                                        <p class="mt-1 text-xs text-rose-700/80 dark:text-rose-200/80">Aksi ini menghapus akun login dari panel admin. Admin aktif dan admin terakhir akan tetap dilindungi.</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <button type="button" wire:click="deleteManagedUser" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                                <i data-lucide="trash-2" class="size-3.5"></i>
                                                Ya, hapus akun
                                            </button>
                                            <button type="button" wire:click="cancelManagedUserDelete" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                                <i data-lucide="x" class="size-3.5"></i>
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </section>
    @endif

    <section class="shortcut-glass-panel rounded-[2rem] p-5 shadow-xl shadow-slate-900/10 sm:p-6">
        <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-900 dark:text-white">Shortcut Library</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Kelola pencarian, filter, dan aksi cepat dari daftar utama. Untuk reorder massal lintas halaman, buka Sort Studio layar penuh.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:flex xl:flex-wrap">
                <label class="shortcut-input-shell flex items-center gap-3 rounded-2xl px-4 py-3 xl:w-72">
                    <i data-lucide="search" class="size-4 text-slate-500 dark:text-slate-400"></i>
                    <input type="text" wire:model.live.debounce.250ms="search" placeholder="Cari shortcut..." class="w-full bg-transparent text-sm outline-none placeholder:text-slate-500 dark:placeholder:text-slate-400">
                </label>

                <select wire:model.live="selectedCategory" class="shortcut-text-input min-w-44 py-3">
                    @foreach ($this->categories as $existingCategory)
                        <option value="{{ $existingCategory }}">{{ $existingCategory === 'All' ? 'All Categories' : $existingCategory }}</option>
                    @endforeach
                </select>

                <select wire:model.live="statusFilter" class="shortcut-text-input min-w-40 py-3">
                    <option value="all">All Status</option>
                    <option value="active">Active Only</option>
                    <option value="hidden">Hidden Only</option>
                </select>

                <button
                    type="button"
                    wire:click="openSortStudio"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300/70 bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 dark:border-sky-400/20"
                >
                    <i data-lucide="arrow-up-down" class="size-4"></i>
                    Open Sort Studio
                </button>
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($this->shortcuts as $shortcut)
                <article
                    wire:key="shortcut-{{ $shortcut->id }}"
                    class="rounded-[1.5rem] border border-slate-300/60 bg-white/70 p-4 shadow-sm transition dark:border-white/10 dark:bg-white/5"
                >
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="flex min-w-0 items-start gap-4">
                            <button type="button" wire:click="openSortStudio" class="mt-1 inline-flex size-10 shrink-0 items-center justify-center rounded-2xl border border-slate-300/70 bg-white/80 text-slate-500 transition hover:-translate-y-0.5 hover:text-sky-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:text-sky-300">
                                <i data-lucide="grip-vertical" class="size-4"></i>
                            </button>

                            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/90 dark:bg-white/10">
                                @if ($shortcut->icon_path)
                                    <img src="{{ $shortcut->icon_path }}" alt="{{ $shortcut->title }} icon" class="size-7 rounded-lg object-cover">
                                @else
                                    <i data-lucide="globe" class="size-5 text-slate-500 dark:text-slate-300"></i>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full border border-slate-300/70 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:border-white/10 dark:text-slate-400">
                                        #{{ $shortcut->sort_order }}
                                    </span>
                                    <h3 class="truncate font-semibold text-slate-900 dark:text-white">{{ $shortcut->title }}</h3>
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] {{ $shortcut->is_active ? 'bg-emerald-500/12 text-emerald-700 dark:text-emerald-300' : 'bg-amber-500/12 text-amber-700 dark:text-amber-300' }}">
                                        {{ $shortcut->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                    <span class="rounded-full border border-slate-300/70 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-600 dark:border-white/10 dark:text-slate-300">
                                        {{ $shortcut->category }}
                                    </span>
                                </div>
                                <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">{{ $shortcut->url }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $shortcut->description ?: 'Tanpa deskripsi.' }}</p>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap gap-2">
                            <a href="{{ $shortcut->url }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                <i data-lucide="external-link" class="size-4"></i>
                                Open
                            </a>
                            <button type="button" wire:click="edit({{ $shortcut->id }})" class="inline-flex items-center gap-2 rounded-full border border-sky-300/70 bg-sky-500/10 px-4 py-2 text-sm font-medium text-sky-700 transition hover:-translate-y-0.5 dark:border-sky-500/20 dark:text-sky-300">
                                <i data-lucide="pencil-line" class="size-4"></i>
                                Edit
                            </button>
                            <button type="button" wire:click="confirmDelete({{ $shortcut->id }})" class="inline-flex items-center gap-2 rounded-full border border-rose-300/70 bg-rose-500/10 px-4 py-2 text-sm font-medium text-rose-700 transition hover:-translate-y-0.5 dark:border-rose-500/20 dark:text-rose-300">
                                <i data-lucide="trash-2" class="size-4"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-slate-300/70 p-10 text-center dark:border-white/10">
                    <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                        <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                            <i data-lucide="inbox" class="size-6"></i>
                        </div>
                        <h3 class="font-display text-xl font-semibold text-slate-900 dark:text-white">Belum ada shortcut</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Coba ubah filter atau tambahkan shortcut baru dari tombol di atas.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $this->shortcuts->links() }}
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center p-4">
            <button type="button" wire:click="closeFormModal" class="absolute inset-0 bg-slate-950/65 backdrop-blur-sm"></button>

            <section class="shortcut-glass-panel relative z-10 w-full max-w-2xl rounded-[2rem] p-5 shadow-2xl shadow-slate-950/30 sm:p-6">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600 dark:text-sky-300">{{ $editingId ? 'Edit Shortcut' : 'Create Shortcut' }}</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold text-slate-900 dark:text-white">{{ $editingId ? 'Perbarui shortcut pilihan' : 'Tambahkan shortcut baru' }}</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Shortcut baru akan otomatis ditaruh di urutan paling bawah dan bisa dipindah setelah disimpan.</p>
                    </div>
                    <button type="button" wire:click="closeFormModal" class="inline-flex size-10 items-center justify-center rounded-full border border-slate-300/70 bg-white/70 text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-[1fr_auto] sm:items-start">
                        <div>
                            <label for="shortcut-title" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Title</label>
                            <input id="shortcut-title" type="text" wire:model.blur="title" class="shortcut-text-input" placeholder="Notion Workspace">
                            @error('title') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        @if ($iconPreview)
                            <div class="flex size-14 items-center justify-center rounded-3xl border border-white/10 bg-white/70 dark:bg-white/8">
                                <img src="{{ $iconPreview }}" alt="Icon preview" class="size-8 rounded-xl object-cover">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="shortcut-url" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">URL</label>
                        <input id="shortcut-url" type="text" wire:model.live.debounce.500ms="url" class="shortcut-text-input" placeholder="https://example.com">
                        @error('url') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="shortcut-category" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Category</label>
                            <input id="shortcut-category" list="shortcut-category-list" type="text" wire:model.blur="category" class="shortcut-text-input" placeholder="Productivity">
                            <datalist id="shortcut-category-list">
                                @foreach ($this->categories as $existingCategory)
                                    @if ($existingCategory !== 'All')
                                        <option value="{{ $existingCategory }}"></option>
                                    @endif
                                @endforeach
                            </datalist>
                            @error('category') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <label class="flex items-center justify-between gap-3 rounded-2xl border border-slate-300/70 bg-white/60 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Shortcut aktif</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Muncul di dashboard publik.</p>
                            </div>
                            <input type="checkbox" wire:model="is_active" class="size-5 rounded border-slate-300 text-sky-500 focus:ring-sky-500">
                        </label>
                    </div>

                    <div>
                        <label for="shortcut-description" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Description</label>
                        <textarea id="shortcut-description" wire:model.blur="description" rows="4" class="shortcut-text-input resize-none" placeholder="Ringkasan singkat untuk fungsi website ini"></textarea>
                        @error('description') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap justify-end gap-3 pt-2">
                        <button type="button" wire:click="resetForm" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                            <i data-lucide="rotate-ccw" class="size-4"></i>
                            Reset
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-900">
                            <i data-lucide="save" class="size-4"></i>
                            {{ $editingId ? 'Update Shortcut' : 'Save Shortcut' }}
                        </button>
                    </div>
                </form>
            </section>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center p-4">
            <button type="button" wire:click="closeDeleteModal" class="absolute inset-0 bg-slate-950/65 backdrop-blur-sm"></button>

            <section class="shortcut-glass-panel relative z-10 w-full max-w-lg rounded-[2rem] p-5 shadow-2xl shadow-slate-950/30 sm:p-6">
                <div class="flex items-start gap-4">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-300">
                        <i data-lucide="trash-2" class="size-5"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-600 dark:text-rose-300">Delete Shortcut</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold text-slate-900 dark:text-white">Hapus {{ $deleteShortcutTitle }}?</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Tindakan ini akan menghapus shortcut dari admin panel dan dashboard publik. Lanjutkan hanya jika memang ingin menghapus permanen.</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-5 py-3 text-sm font-semibold text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                        <i data-lucide="arrow-left" class="size-4"></i>
                        Cancel
                    </button>
                    <button type="button" wire:click="delete" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white">
                        <i data-lucide="trash-2" class="size-4"></i>
                        Delete Permanently
                    </button>
                </div>
            </section>
        </div>
    @endif

    @if ($showSortStudio)
        <div class="fixed inset-0 z-50" x-data="shortcutSorter()">
            <button type="button" wire:click="closeSortStudio" class="absolute inset-0 bg-slate-950/80 backdrop-blur-md"></button>

            <section class="absolute inset-0 overflow-y-auto">
                <div class="mx-auto flex min-h-full w-full max-w-7xl flex-col gap-6 p-4 sm:p-6">
                    <div class="shortcut-glass-panel relative mt-4 rounded-[2rem] p-5 shadow-2xl shadow-slate-950/40 sm:p-6">
                        <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                            <div class="max-w-3xl space-y-3">
                                <span class="inline-flex items-center gap-2 rounded-full border border-sky-300/70 bg-sky-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700 dark:border-sky-500/20 dark:text-sky-300">
                                    <i data-lucide="scan-search" class="size-4"></i>
                                    Sort Studio
                                </span>
                                <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Susun ulang semua shortcut dalam satu kanvas.</h2>
                                <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Semua hasil filter dimuat sekaligus. Tarik kartu ke posisi baru untuk memperbarui urutan publik secara instan.</p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <div class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                    <i data-lucide="layers-3" class="size-4"></i>
                                    {{ $this->sortableShortcuts->count() }} filtered shortcuts
                                </div>
                                <button type="button" wire:click="requestResetSeedOrder" class="inline-flex items-center gap-2 rounded-full border border-amber-300/70 bg-amber-500/10 px-4 py-2 text-sm font-medium text-amber-700 transition hover:-translate-y-0.5 dark:border-amber-500/20 dark:text-amber-300">
                                    <i data-lucide="rotate-ccw" class="size-4"></i>
                                    Reset Seed Order
                                </button>
                                <button type="button" wire:click="closeSortStudio" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white dark:text-slate-900">
                                    <i data-lucide="check" class="size-4"></i>
                                    Close Studio
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 lg:grid-cols-[minmax(0,1fr)_220px_220px]">
                            <label class="shortcut-input-shell flex items-center gap-3 rounded-2xl px-4 py-3">
                                <i data-lucide="search" class="size-4 text-slate-500 dark:text-slate-400"></i>
                                <input type="text" wire:model.live.debounce.250ms="search" placeholder="Cari untuk mempersempit urutan..." class="w-full bg-transparent text-sm outline-none placeholder:text-slate-500 dark:placeholder:text-slate-400">
                            </label>

                            <select wire:model.live="selectedCategory" class="shortcut-text-input py-3">
                                @foreach ($this->categories as $existingCategory)
                                    <option value="{{ $existingCategory }}">{{ $existingCategory === 'All' ? 'All Categories' : $existingCategory }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live="statusFilter" class="shortcut-text-input py-3">
                                <option value="all">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="hidden">Hidden Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 pb-6">
                        @forelse ($this->sortableShortcuts as $shortcut)
                            <article
                                wire:key="sortable-shortcut-{{ $shortcut->id }}"
                                draggable="true"
                                x-on:dragstart="start($event, {{ $shortcut->id }})"
                                x-on:dragend="finish()"
                                x-on:dragover.prevent="over($event, {{ $shortcut->id }})"
                                x-on:drop.prevent="drop({{ $shortcut->id }}, $wire)"
                                class="shortcut-glass-panel rounded-[1.75rem] p-4 shadow-lg shadow-slate-950/10 transition sm:p-5"
                                :class="{
                                    'opacity-60 scale-[0.99]': draggingId === {{ $shortcut->id }},
                                    'ring-2 ring-sky-400/50 -translate-y-0.5': dragOverId === {{ $shortcut->id }} && draggingId !== {{ $shortcut->id }}
                                }"
                            >
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="flex min-w-0 items-start gap-4">
                                        <div class="inline-flex size-12 shrink-0 items-center justify-center rounded-2xl border border-slate-300/70 bg-white/80 text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 cursor-grab active:cursor-grabbing">
                                            <i data-lucide="grip" class="size-5"></i>
                                        </div>

                                        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/90 dark:bg-white/10">
                                            @if ($shortcut->icon_path)
                                                <img src="{{ $shortcut->icon_path }}" alt="{{ $shortcut->title }} icon" class="size-7 rounded-lg object-cover">
                                            @else
                                                <i data-lucide="globe" class="size-5 text-slate-500 dark:text-slate-300"></i>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full border border-slate-300/70 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:border-white/10 dark:text-slate-400">
                                                    #{{ $shortcut->sort_order }}
                                                </span>
                                                <h3 class="truncate font-semibold text-slate-900 dark:text-white">{{ $shortcut->title }}</h3>
                                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] {{ $shortcut->is_active ? 'bg-emerald-500/12 text-emerald-700 dark:text-emerald-300' : 'bg-amber-500/12 text-amber-700 dark:text-amber-300' }}">
                                                    {{ $shortcut->is_active ? 'Active' : 'Hidden' }}
                                                </span>
                                                <span class="rounded-full border border-slate-300/70 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-600 dark:border-white/10 dark:text-slate-300">
                                                    {{ $shortcut->category }}
                                                </span>
                                            </div>
                                            <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">{{ $shortcut->url }}</p>
                                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $shortcut->description ?: 'Tanpa deskripsi.' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex shrink-0 flex-wrap gap-2">
                                        <a href="{{ $shortcut->url }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                            <i data-lucide="external-link" class="size-4"></i>
                                            Open
                                        </a>
                                        <button type="button" wire:click="edit({{ $shortcut->id }})" class="inline-flex items-center gap-2 rounded-full border border-sky-300/70 bg-sky-500/10 px-4 py-2 text-sm font-medium text-sky-700 transition hover:-translate-y-0.5 dark:border-sky-500/20 dark:text-sky-300">
                                            <i data-lucide="pencil-line" class="size-4"></i>
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="shortcut-glass-panel rounded-[1.75rem] border border-dashed border-slate-300/70 p-10 text-center dark:border-white/10">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                                        <i data-lucide="inbox" class="size-6"></i>
                                    </div>
                                    <h3 class="font-display text-xl font-semibold text-slate-900 dark:text-white">Tidak ada hasil untuk diurutkan</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-300">Ubah pencarian atau filter, lalu coba lagi.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    @endif

    @if ($showResetOrderModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <button type="button" wire:click="closeResetOrderModal" class="absolute inset-0 bg-slate-950/75 backdrop-blur-sm"></button>

            <section class="shortcut-glass-panel relative z-10 w-full max-w-lg rounded-[2rem] p-5 shadow-2xl shadow-slate-950/30 sm:p-6">
                <div class="flex items-start gap-4">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-300">
                        <i data-lucide="rotate-ccw" class="size-5"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-600 dark:text-amber-300">Reset Order</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold text-slate-900 dark:text-white">Kembalikan urutan shortcut bawaan?</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Shortcut seed akan kembali ke urutan default. Shortcut custom tetap dipertahankan, tetapi dipindah ke bawah setelah item bawaan.</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap justify-end gap-3">
                    <button type="button" wire:click="closeResetOrderModal" class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/70 px-5 py-3 text-sm font-semibold text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                        <i data-lucide="arrow-left" class="size-4"></i>
                        Cancel
                    </button>
                    <button type="button" wire:click="resetSeedOrder" class="inline-flex items-center gap-2 rounded-full bg-amber-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5">
                        <i data-lucide="check" class="size-4"></i>
                        Confirm Reset
                    </button>
                </div>
            </section>
        </div>
    @endif

    <script>
        function shortcutToastCenter() {
            return {
                toasts: [],
                push(detail) {
                    const toast = {
                        id: Date.now() + Math.random(),
                        message: detail.message,
                        type: detail.type || 'info',
                        visible: true,
                    };

                    this.toasts.push(toast);

                    setTimeout(() => {
                        toast.visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter((item) => item.id !== toast.id);
                        }, 250);
                    }, 2600);
                },
            };
        }

        function shortcutSorter() {
            return {
                draggingId: null,
                dragOverId: null,
                start(event, id) {
                    if (! event.target.closest('[draggable="true"]')) {
                        return;
                    }

                    this.draggingId = id;
                },
                over(event, id) {
                    if (! this.draggingId) {
                        return;
                    }

                    this.dragOverId = id;
                },
                async drop(id, wire) {
                    if (! this.draggingId || this.draggingId === id) {
                        this.finish();
                        return;
                    }

                    await wire.reorderShortcut(this.draggingId, id);
                    this.finish();
                },
                finish() {
                    this.draggingId = null;
                    this.dragOverId = null;
                },
            };
        }
    </script>
</div>
