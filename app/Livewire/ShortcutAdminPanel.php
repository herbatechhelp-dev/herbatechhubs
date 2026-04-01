<?php

namespace App\Livewire;

use App\Models\HubSetting;
use App\Models\Shortcut;
use App\Models\User;
use App\Services\FaviconFetcher;
use Database\Seeders\ShortcutSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

#[Title('Shortcut Admin')]
class ShortcutAdminPanel extends Component
{
    use WithFileUploads;
    use WithPagination;

    public ?int $editingId = null;

    public ?int $deleteShortcutId = null;

    public string $title = '';

    public string $url = '';

    public string $description = '';

    public string $category = '';

    public bool $is_active = true;

    public string $search = '';

    public string $selectedCategory = 'All';

    public string $statusFilter = 'all';

    public bool $sortingMode = false;

    public bool $showSortStudio = false;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public bool $showResetOrderModal = false;

    public ?string $iconPreview = null;

    public $iconUpload = null;

    public string $dashboardTitle = '';

    public ?string $dashboardSubtitle = '';

    public string $dashboardDescription = '';

    public string $dashboardFaviconUrl = '';

    public ?string $dashboardFaviconPreview = null;

    public ?string $dashboardLogoPreview = null;

    public $dashboardFaviconUpload = null;

    public $dashboardLogoUpload = null;

    public bool $dashboardHasUploadedFavicon = false;

    public bool $dashboardHasUploadedLogo = false;

    public float $dashboardLogoZoom = 1;

    public int $dashboardLogoPositionX = 50;

    public int $dashboardLogoPositionY = 50;

    public string $deleteShortcutTitle = '';

    public string $managedUserName = '';

    public string $managedUserEmail = '';

    public string $managedUserPassword = '';

    public string $managedUserRole = User::ROLE_MANAGER;

    public ?int $editingManagedUserId = null;

    public ?int $deleteManagedUserId = null;

    public string $deleteManagedUserName = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->canAccessAdmin(), 403);

        $this->fillDashboardSettings();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:2048'],
            'description' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'iconUpload' => [
                'nullable',
                'image',
                'max:2048',
                function (string $attribute, $value, \Closure $fail): void {
                    if (! $value || ! $this->imageIsSquare($value)) {
                        if ($value) {
                            $fail('Logo shortcut harus menggunakan rasio persegi (1:1).');
                        }
                    }
                },
            ],
        ];
    }

    public function dashboardSettingsRules(): array
    {
        return [
            'dashboardTitle' => ['required', 'string', 'max:255'],
            'dashboardSubtitle' => ['nullable', 'string', 'max:255'],
            'dashboardDescription' => ['required', 'string', 'max:1000'],
            'dashboardFaviconUrl' => ['nullable', 'url', 'max:2048'],
            'dashboardFaviconUpload' => [
                'nullable',
                'image',
                'max:2048',
                function (string $attribute, $value, \Closure $fail): void {
                    if (! $value || ! $this->imageIsSquare($value)) {
                        if ($value) {
                            $fail('Favicon upload must use a square image ratio.');
                        }
                    }
                },
            ],
            'dashboardLogoUpload' => [
                'nullable',
                'image',
                'max:4096',
                function (string $attribute, $value, \Closure $fail): void {
                    if (! $value || $this->imageHasBalancedLogoRatio($value)) {
                        return;
                    }

                    $fail('Dashboard logo must use a square or near-square ratio.');
                },
            ],
            'dashboardLogoZoom' => ['required', 'numeric', 'min:1', 'max:2.5'],
            'dashboardLogoPositionX' => ['required', 'integer', 'min:0', 'max:100'],
            'dashboardLogoPositionY' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function managedUserRules(): array
    {
        $rules = [
            'managedUserName' => ['required', 'string', 'max:255'],
            'managedUserEmail' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingManagedUserId)],
            'managedUserRole' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_MANAGER])],
        ];

        if ($this->editingManagedUserId) {
            if (filled($this->managedUserPassword)) {
                $rules['managedUserPassword'] = ['string', 'min:8', 'max:255'];
            }

            return $rules;
        }

        $rules['managedUserPassword'] = ['required', 'string', 'min:8', 'max:255'];

        return $rules;
    }

    public function updatedDashboardFaviconUrl(): void
    {
        $this->dashboardFaviconPreview = trim($this->dashboardFaviconUrl) !== ''
            ? trim($this->dashboardFaviconUrl)
            : '/favicon.ico';
    }

    public function updatedIconUpload(): void
    {
        if ($this->iconUpload) {
            $this->iconPreview = $this->iconUpload->temporaryUrl();
        }
    }

    public function updatedDashboardFaviconUpload(): void
    {
        if ($this->dashboardFaviconUpload) {
            $this->dashboardFaviconPreview = $this->dashboardFaviconUpload->temporaryUrl();
        }
    }

    public function updatedDashboardLogoUpload(): void
    {
        if ($this->dashboardLogoUpload) {
            $this->dashboardLogoPreview = $this->dashboardLogoUpload->temporaryUrl();
        }
    }

    public function resetLogoCrop(): void
    {
        $this->dashboardLogoZoom = 1;
        $this->dashboardLogoPositionX = 50;
        $this->dashboardLogoPositionY = 50;
    }

    public function updatedUrl(): void
    {
        if (! $this->iconUpload) {
            $this->iconPreview = app(FaviconFetcher::class)->preview($this->url);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function toggleSortingMode(): void
    {
        if ($this->sortingMode || $this->showSortStudio) {
            $this->closeSortStudio();

            return;
        }

        $this->openSortStudio();
    }

    public function openSortStudio(): void
    {
        $this->sortingMode = true;
        $this->showSortStudio = true;
        $this->resetPage();
    }

    public function closeSortStudio(): void
    {
        $this->sortingMode = false;
        $this->showSortStudio = false;
    }

    #[Computed]
    public function categories(): array
    {
        return Shortcut::query()
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
        return $this->filteredShortcutsQuery()
            ->paginate(6);
    }

    #[Computed]
    public function sortableShortcuts()
    {
        return $this->filteredShortcutsQuery()->get();
    }

    protected function filteredShortcutsQuery(): Builder
    {
        return Shortcut::query()
            ->when($this->search !== '', function ($query): void {
                $term = '%'.$this->search.'%';

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery
                        ->where('title', 'like', $term)
                        ->orWhere('url', 'like', $term)
                        ->orWhere('category', 'like', $term);
                });
            })
            ->when($this->selectedCategory !== 'All', fn ($query) => $query->where('category', $this->selectedCategory))
            ->when($this->statusFilter !== 'all', fn ($query) => $query->where('is_active', $this->statusFilter === 'active'))
                ->orderBy('sort_order')
                ->orderBy('title');
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'total' => Shortcut::query()->count(),
            'active' => Shortcut::query()->where('is_active', true)->count(),
            'hidden' => Shortcut::query()->where('is_active', false)->count(),
            'categories' => Shortcut::query()->distinct('category')->count('category'),
        ];
    }

    #[Computed]
    public function manageableUsers()
    {
        return $this->manageableUsersQuery()
            ->orderByRaw("case when role = 'admin' then 0 when role = 'manager' then 1 else 2 end")
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function canManageUsers(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function saveManagedUser(): void
    {
        abort_unless($this->canManageUsers, 403);

        $validated = $this->validate($this->managedUserRules());

        $managedUser = $this->editingManagedUserId
            ? $this->findManageableUser($this->editingManagedUserId)
            : null;

        if ($managedUser && $managedUser->is(auth()->user()) && $validated['managedUserRole'] !== User::ROLE_ADMIN) {
            $this->addError('managedUserRole', 'Akun admin yang sedang dipakai harus tetap berperan sebagai admin.');

            return;
        }

        if ($managedUser && $managedUser->isAdmin() && $validated['managedUserRole'] !== User::ROLE_ADMIN && $this->adminUserCount() <= 1) {
            $this->addError('managedUserRole', 'Setidaknya harus ada satu admin aktif di sistem.');

            return;
        }

        $payload = [
            'name' => trim($validated['managedUserName']),
            'email' => trim($validated['managedUserEmail']),
            'role' => $validated['managedUserRole'],
        ];

        if (filled($this->managedUserPassword)) {
            $payload['password'] = $validated['managedUserPassword'];
        }

        if ($managedUser) {
            $managedUser->update($payload);
        } else {
            User::query()->create([
                ...$payload,
                'email_verified_at' => now(),
                'password' => $validated['managedUserPassword'],
            ]);
        }

        unset($this->manageableUsers);

        $this->resetManagedUserForm();

        $this->dispatch(
            'shortcut-toast',
            message: $managedUser ? 'Akun pengelola berhasil diperbarui.' : 'Akun pengelola berhasil dibuat.',
            type: 'success',
        );
    }

    public function editManagedUser(int $managedUserId): void
    {
        abort_unless($this->canManageUsers, 403);

        $managedUser = $this->findManageableUser($managedUserId);

        $this->editingManagedUserId = $managedUser->id;
        $this->managedUserName = $managedUser->name;
        $this->managedUserEmail = $managedUser->email;
        $this->managedUserPassword = '';
        $this->managedUserRole = $managedUser->role;
        $this->cancelManagedUserDelete();
        $this->resetValidation(['managedUserName', 'managedUserEmail', 'managedUserPassword', 'managedUserRole']);

        $this->dispatch('shortcut-toast', message: 'Akun siap diedit.', type: 'info');
    }

    public function confirmManagedUserDelete(int $managedUserId): void
    {
        abort_unless($this->canManageUsers, 403);

        $managedUser = $this->findManageableUser($managedUserId);

        $this->deleteManagedUserId = $managedUser->id;
        $this->deleteManagedUserName = $managedUser->name;
    }

    public function cancelManagedUserDelete(): void
    {
        $this->reset(['deleteManagedUserId', 'deleteManagedUserName']);
    }

    public function deleteManagedUser(): void
    {
        abort_unless($this->canManageUsers, 403);

        if (! $this->deleteManagedUserId) {
            return;
        }

        $managedUser = $this->findManageableUser($this->deleteManagedUserId);

        if ($managedUser->is(auth()->user())) {
            $this->dispatch('shortcut-toast', message: 'Akun yang sedang dipakai tidak bisa dihapus.', type: 'warning');

            return;
        }

        if ($managedUser->isAdmin() && $this->adminUserCount() <= 1) {
            $this->dispatch('shortcut-toast', message: 'Admin terakhir tidak bisa dihapus.', type: 'warning');

            return;
        }

        $managedUser->delete();

        if ($this->editingManagedUserId === $managedUser->id) {
            $this->resetManagedUserForm();
        }

        unset($this->manageableUsers);

        $this->cancelManagedUserDelete();

        $this->dispatch('shortcut-toast', message: 'Akun pengelola berhasil dihapus.', type: 'success');
    }

    public function saveDashboardSettings(): void
    {
        $validated = $this->validate($this->dashboardSettingsRules());

        $settings = HubSetting::query()->firstOrNew(['id' => 1]);

        if ($this->dashboardFaviconUpload) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }

            $settings->favicon_path = $this->dashboardFaviconUpload->store('hub-assets', 'public');
            $settings->favicon_url = null;
        }

        if ($this->dashboardLogoUpload) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            $settings->logo_path = $this->dashboardLogoUpload->store('hub-assets', 'public');
        }

        $settings->fill([
            'title' => trim($validated['dashboardTitle']),
            'subtitle' => isset($validated['dashboardSubtitle']) ? trim($validated['dashboardSubtitle']) : null,
            'description' => trim($validated['dashboardDescription']),
            'favicon_url' => $this->dashboardFaviconUpload ? null : (filled($validated['dashboardFaviconUrl']) ? trim($validated['dashboardFaviconUrl']) : null),
            'logo_zoom' => (float) $validated['dashboardLogoZoom'],
            'logo_position_x' => (int) $validated['dashboardLogoPositionX'],
            'logo_position_y' => (int) $validated['dashboardLogoPositionY'],
        ]);
        $settings->save();

        $this->fillDashboardSettings();
        $this->dashboardFaviconUpload = null;
        $this->dashboardLogoUpload = null;

        $this->dispatch('shortcut-toast', message: 'Dashboard settings updated.', type: 'success');
    }

    public function removeDashboardFavicon(): void
    {
        $settings = HubSetting::query()->first();

        if (! $settings?->hasUploadedFavicon()) {
            return;
        }

        Storage::disk('public')->delete($settings->favicon_path);

        $settings->update(['favicon_path' => null]);

        $this->dashboardFaviconUpload = null;
        $this->fillDashboardSettings();

        $this->dispatch('shortcut-toast', message: 'Uploaded favicon removed.', type: 'success');
    }

    public function removeDashboardLogo(): void
    {
        $settings = HubSetting::query()->first();

        if (! $settings?->hasUploadedLogo()) {
            return;
        }

        Storage::disk('public')->delete($settings->logo_path);

        $settings->update(['logo_path' => null]);

        $this->dashboardLogoUpload = null;
        $this->resetLogoCrop();
        $this->fillDashboardSettings();

        $this->dispatch('shortcut-toast', message: 'Uploaded dashboard logo removed.', type: 'success');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function edit(int $shortcutId): void
    {
        $shortcut = Shortcut::findOrFail($shortcutId);

        $this->editingId = $shortcut->id;
        $this->title = $shortcut->title;
        $this->url = $shortcut->url;
        $this->description = $shortcut->description ?? '';
        $this->category = $shortcut->category;
        $this->is_active = $shortcut->is_active;
        $this->iconPreview = $shortcut->resolvedIconUrl();
        $this->iconUpload = null;
        $this->showFormModal = true;

        $this->dispatch('shortcut-toast', message: 'Shortcut loaded for editing.', type: 'info');
    }

    public function save(): void
    {
        $validated = $this->validate();

        $faviconFetcher = app(FaviconFetcher::class);

        $normalizedUrl = $faviconFetcher->normalizeUrl($validated['url']);

        if (! $normalizedUrl) {
            $this->addError('url', 'Enter a valid URL.');

            return;
        }

        $payload = [
            'title' => trim($validated['title']),
            'url' => $normalizedUrl,
            'description' => $validated['description'] !== '' ? trim($validated['description']) : null,
            'category' => trim($validated['category']),
            'is_active' => $validated['is_active'],
        ];

        if ($this->iconUpload) {
            if ($this->editingId) {
                $existing = Shortcut::query()->find($this->editingId);
                if ($existing && $existing->icon_path && !str_starts_with($existing->icon_path, 'http://') && !str_starts_with($existing->icon_path, 'https://')) {
                    Storage::disk('public')->delete($existing->icon_path);
                }
            }
            $payload['icon_path'] = $this->iconUpload->store('shortcut-icons', 'public');
        } else {
            if ($this->editingId) {
                $existing = Shortcut::query()->findOrFail($this->editingId);
                if ($existing->icon_path && !str_starts_with($existing->icon_path, 'http://') && !str_starts_with($existing->icon_path, 'https://')) {
                    $payload['icon_path'] = $existing->icon_path;
                } else {
                    $payload['icon_path'] = $faviconFetcher->preview($normalizedUrl) ?? $faviconFetcher->fallbackIcon($normalizedUrl);
                }
            } else {
                $payload['icon_path'] = $faviconFetcher->preview($normalizedUrl) ?? $faviconFetcher->fallbackIcon($normalizedUrl);
            }
        }

        if ($this->editingId) {
            $shortcut = Shortcut::query()->findOrFail($this->editingId);
            $shortcut->update($payload);
        } else {
            $shortcut = Shortcut::query()->create([
                ...$payload,
                'sort_order' => (int) Shortcut::query()->max('sort_order') + 1,
            ]);
        }

        $this->iconPreview = $shortcut->resolvedIconUrl();
        $this->dispatch(
            'shortcut-toast',
            message: $this->editingId ? 'Shortcut updated successfully.' : 'Shortcut created successfully.',
            type: 'success',
        );

        $this->resetForm();
    }

    public function confirmDelete(int $shortcutId): void
    {
        $shortcut = Shortcut::findOrFail($shortcutId);

        $this->deleteShortcutId = $shortcut->id;
        $this->deleteShortcutTitle = $shortcut->title;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->deleteShortcutId) {
            return;
        }

        $shortcut = Shortcut::findOrFail($this->deleteShortcutId);
        
        if ($shortcut->icon_path && !str_starts_with($shortcut->icon_path, 'http://') && !str_starts_with($shortcut->icon_path, 'https://')) {
            Storage::disk('public')->delete($shortcut->icon_path);
        }

        $shortcut->delete();

        if ($this->editingId === $this->deleteShortcutId) {
            $this->resetForm();
        }

        $this->closeDeleteModal();

        $this->dispatch('shortcut-toast', message: 'Shortcut deleted.', type: 'success');
    }

    public function removeCustomIcon(): void
    {
        if ($this->editingId) {
            $shortcut = Shortcut::findOrFail($this->editingId);
            if ($shortcut->icon_path && !str_starts_with($shortcut->icon_path, 'http://') && !str_starts_with($shortcut->icon_path, 'https://')) {
                Storage::disk('public')->delete($shortcut->icon_path);
                
                $faviconFetcher = app(FaviconFetcher::class);
                $shortcut->update([
                    'icon_path' => $faviconFetcher->preview($shortcut->url) ?? $faviconFetcher->fallbackIcon($shortcut->url)
                ]);
                
                $this->iconPreview = $shortcut->resolvedIconUrl();
            }
        }
        
        $this->iconUpload = null;
        $this->dispatch('shortcut-toast', message: 'Uploaded icon removed.', type: 'success');
    }

    public function cancelIconUpload(): void
    {
        $this->iconUpload = null;
        if ($this->editingId) {
             $this->iconPreview = Shortcut::findOrFail($this->editingId)->resolvedIconUrl();
        } else {
             $this->updatedUrl();
        }
    }

    public function requestResetSeedOrder(): void
    {
        $this->showResetOrderModal = true;
    }

    public function resetSeedOrder(): void
    {
        if (! $this->showResetOrderModal) {
            return;
        }

        $seededShortcuts = collect(ShortcutSeeder::shortcuts())
            ->sortBy('sort_order')
            ->values();

        $seededUrls = $seededShortcuts->pluck('url')->all();

        $existingSeededShortcuts = Shortcut::query()
            ->whereIn('url', $seededUrls)
            ->get()
            ->keyBy('url');

        if ($existingSeededShortcuts->isEmpty()) {
            $this->closeResetOrderModal();
            $this->dispatch('shortcut-toast', message: 'No seeded shortcuts found to reset.', type: 'info');

            return;
        }

        $nextSortOrder = 1;

        $seededShortcuts->each(function (array $definition) use ($existingSeededShortcuts, &$nextSortOrder): void {
            $shortcut = $existingSeededShortcuts->get($definition['url']);

            if (! $shortcut) {
                return;
            }

            $shortcut->update(['sort_order' => $nextSortOrder]);

            $nextSortOrder++;
        });

        Shortcut::query()
            ->when($seededUrls !== [], fn ($query) => $query->whereNotIn('url', $seededUrls))
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->each(function (Shortcut $shortcut) use (&$nextSortOrder): void {
                $shortcut->update(['sort_order' => $nextSortOrder]);

                $nextSortOrder++;
            });

        unset($this->shortcuts, $this->sortableShortcuts);

        $this->closeResetOrderModal();

        $this->dispatch('shortcut-toast', message: 'Shortcut order reset to seed defaults.', type: 'success');
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
    }

    public function closeDeleteModal(): void
    {
        $this->reset(['deleteShortcutId', 'deleteShortcutTitle', 'showDeleteModal']);
    }

    public function closeResetOrderModal(): void
    {
        $this->showResetOrderModal = false;
    }

    public function resetManagedUserForm(): void
    {
        $this->reset(['editingManagedUserId', 'managedUserName', 'managedUserEmail', 'managedUserPassword']);
        $this->managedUserRole = User::ROLE_MANAGER;
        $this->cancelManagedUserDelete();
        $this->resetValidation(['managedUserName', 'managedUserEmail', 'managedUserPassword', 'managedUserRole']);
    }

    protected function manageableUsersQuery(): Builder
    {
        return User::query()->whereIn('role', User::adminRoles());
    }

    protected function findManageableUser(int $managedUserId): User
    {
        return $this->manageableUsersQuery()->findOrFail($managedUserId);
    }

    protected function adminUserCount(): int
    {
        return User::query()->where('role', User::ROLE_ADMIN)->count();
    }

    protected function imageIsSquare($file): bool
    {
        [$width, $height] = $this->imageDimensions($file);

        return $width > 0 && $width === $height;
    }

    protected function imageHasBalancedLogoRatio($file): bool
    {
        [$width, $height] = $this->imageDimensions($file);

        if ($width === 0 || $height === 0) {
            return false;
        }

        $ratio = $width / $height;

        return $ratio >= 0.8 && $ratio <= 1.25;
    }

    protected function imageDimensions($file): array
    {
        $path = $file?->getRealPath();

        if (! $path) {
            return [0, 0];
        }

        $dimensions = @getimagesize($path);

        if (! is_array($dimensions)) {
            return [0, 0];
        }

        return [(int) ($dimensions[0] ?? 0), (int) ($dimensions[1] ?? 0)];
    }

    protected function fillDashboardSettings(): void
    {
        $settings = HubSetting::current();

        $this->dashboardTitle = $settings->title;
        $this->dashboardSubtitle = $settings->subtitle;
        $this->dashboardDescription = $settings->description;
        $this->dashboardFaviconUrl = $settings->favicon_url ?? '';
        $this->dashboardFaviconPreview = $settings->resolvedFaviconUrl();
        $this->dashboardLogoPreview = $settings->resolvedLogoUrl();
        $this->dashboardHasUploadedFavicon = $settings->hasUploadedFavicon();
        $this->dashboardHasUploadedLogo = $settings->hasUploadedLogo();
        $this->dashboardLogoZoom = $settings->resolvedLogoZoom();
        $this->dashboardLogoPositionX = $settings->resolvedLogoPositionX();
        $this->dashboardLogoPositionY = $settings->resolvedLogoPositionY();
    }

    public function reorderShortcut(int $movedShortcutId, int $targetShortcutId): void
    {
        if ($movedShortcutId === $targetShortcutId) {
            return;
        }

        $visibleShortcuts = $this->sortableShortcuts->values();
        $visibleIds = $visibleShortcuts->pluck('id')->all();

        if (! in_array($movedShortcutId, $visibleIds, true) || ! in_array($targetShortcutId, $visibleIds, true)) {
            return;
        }

        $reorderedIds = collect($visibleIds)
            ->reject(fn (int $id) => $id === $movedShortcutId)
            ->values();

        $targetIndex = $reorderedIds->search($targetShortcutId);

        if ($targetIndex === false) {
            return;
        }

        $reorderedIds->splice($targetIndex, 0, [$movedShortcutId]);

        $currentOrders = $visibleShortcuts
            ->pluck('sort_order')
            ->sort()
            ->values();

        $reorderedIds->values()->each(function (int $shortcutId, int $index) use ($currentOrders): void {
            Shortcut::query()
                ->whereKey($shortcutId)
                ->update(['sort_order' => $currentOrders[$index] ?? ($index + 1)]);
        });

        unset($this->shortcuts, $this->sortableShortcuts);

        $this->dispatch('shortcut-toast', message: 'Shortcut order updated.', type: 'success');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'url', 'description', 'category', 'iconPreview', 'iconUpload']);
        $this->resetErrorBag();
        $this->is_active = true;
        $this->showFormModal = false;
    }

    public function render(): View
    {
        return view('livewire.shortcut-admin-panel')
            ->layout('layouts.app', ['title' => $this->dashboardTitle.' Admin']);
    }
}