<?php

use App\Livewire\ShortcutAdminPanel;
use App\Models\HubSetting;
use App\Models\Shortcut;
use App\Models\User;
use Database\Seeders\ShortcutSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('public users can view the shortcut hub', function () {
    Shortcut::factory()->create([
        'title' => 'Laravel Docs',
        'category' => 'Reference',
    ]);

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Personal Shortcut Hub')
        ->assertSee('Laravel Docs');
});

test('admin can update dashboard settings and public hub reflects them', function () {
    $user = User::factory()->admin()->create();

    Storage::fake('public');

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->set('dashboardTitle', 'Workspace Launchpad')
        ->set('dashboardDescription', 'Semua link penting tim produk dalam satu halaman utama.')
        ->set('dashboardFaviconUpload', UploadedFile::fake()->image('workspace-favicon.png', 64, 64))
        ->set('dashboardLogoUpload', UploadedFile::fake()->image('workspace-logo.png', 512, 512))
        ->set('dashboardLogoZoom', 1.4)
        ->set('dashboardLogoPositionX', 65)
        ->set('dashboardLogoPositionY', 35)
        ->call('saveDashboardSettings');

    $settings = HubSetting::query()->first();

    expect($settings)->not->toBeNull();
    expect($settings->title)->toBe('Workspace Launchpad');
    expect($settings->description)->toBe('Semua link penting tim produk dalam satu halaman utama.');
    expect($settings->favicon_path)->not->toBeNull();
    expect($settings->logo_path)->not->toBeNull();
    expect((float) $settings->logo_zoom)->toBe(1.4);
    expect($settings->logo_position_x)->toBe(65);
    expect($settings->logo_position_y)->toBe(35);

    Storage::disk('public')->assertExists($settings->favicon_path);
    Storage::disk('public')->assertExists($settings->logo_path);

    $response = $this->get(route('home'));

    $adminResponse = $this->actingAs($user)->get(route('dashboard'));

    $response
        ->assertOk()
        ->assertSee('Workspace Launchpad')
        ->assertSee('Semua link penting tim produk dalam satu halaman utama.')
        ->assertSee('/storage/'.$settings->favicon_path)
        ->assertSee('/storage/'.$settings->logo_path)
        ->assertSee('object-position: 65% 35%; transform: scale(1.40);');

    $adminResponse
        ->assertOk()
        ->assertSee('Workspace Launchpad Admin')
        ->assertSee('/storage/'.$settings->logo_path);
});

test('admin can remove uploaded dashboard brand assets', function () {
    $user = User::factory()->admin()->create();

    Storage::fake('public');

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->set('dashboardTitle', 'Workspace Launchpad')
        ->set('dashboardDescription', 'Semua link penting tim produk dalam satu halaman utama.')
        ->set('dashboardFaviconUpload', UploadedFile::fake()->image('workspace-favicon.png', 64, 64))
        ->set('dashboardLogoUpload', UploadedFile::fake()->image('workspace-logo.png', 512, 512))
        ->call('saveDashboardSettings');

    $settings = HubSetting::query()->firstOrFail();
    $faviconPath = $settings->favicon_path;
    $logoPath = $settings->logo_path;

    Livewire::test(ShortcutAdminPanel::class)
        ->call('removeDashboardFavicon')
        ->call('removeDashboardLogo');

    $settings->refresh();

    expect($settings->favicon_path)->toBeNull();
    expect($settings->logo_path)->toBeNull();

    Storage::disk('public')->assertMissing($faviconPath);
    Storage::disk('public')->assertMissing($logoPath);
});

test('admin cannot upload a non-square favicon', function () {
    $user = User::factory()->admin()->create();

    Storage::fake('public');

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->set('dashboardTitle', 'Workspace Launchpad')
        ->set('dashboardDescription', 'Semua link penting tim produk dalam satu halaman utama.')
        ->set('dashboardFaviconUpload', UploadedFile::fake()->image('favicon-wide.png', 128, 64))
        ->call('saveDashboardSettings')
        ->assertHasErrors(['dashboardFaviconUpload']);
});

test('admin cannot upload an overly wide dashboard logo', function () {
    $user = User::factory()->admin()->create();

    Storage::fake('public');

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->set('dashboardTitle', 'Workspace Launchpad')
        ->set('dashboardDescription', 'Semua link penting tim produk dalam satu halaman utama.')
        ->set('dashboardLogoUpload', UploadedFile::fake()->image('logo-wide.png', 1200, 400))
        ->call('saveDashboardSettings')
        ->assertHasErrors(['dashboardLogoUpload']);
});

test('guests are redirected away from the admin panel', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view the shortcut admin panel', function () {
    $user = User::factory()->manager()->create();

    Shortcut::factory()->count(7)->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response
        ->assertOk()
        ->assertSee('Personal Shortcut Hub Admin')
        ->assertSee('All Categories')
        ->assertSee('Next');
});

test('admin can reorder shortcuts and dashboard follows saved order', function () {
    $user = User::factory()->manager()->create();

    $first = Shortcut::factory()->create([
        'title' => 'First Shortcut',
        'sort_order' => 1,
        'is_active' => true,
    ]);
    $second = Shortcut::factory()->create([
        'title' => 'Second Shortcut',
        'sort_order' => 2,
        'is_active' => true,
    ]);
    $third = Shortcut::factory()->create([
        'title' => 'Third Shortcut',
        'sort_order' => 3,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('reorderShortcut', $third->id, $first->id);

    expect($third->fresh()->sort_order)->toBe(1);
    expect($first->fresh()->sort_order)->toBe(2);
    expect($second->fresh()->sort_order)->toBe(3);

    $response = $this->get(route('home'));
    $content = $response->getContent();

    expect(strpos($content, 'Third Shortcut'))->toBeLessThan(strpos($content, 'First Shortcut'));
    expect(strpos($content, 'First Shortcut'))->toBeLessThan(strpos($content, 'Second Shortcut'));
});

test('sorting mode allows reordering shortcuts beyond the current paginated page', function () {
    $user = User::factory()->manager()->create();

    $shortcuts = collect(range(1, 8))->map(function (int $position) {
        return Shortcut::factory()->create([
            'title' => 'Shortcut '.$position,
            'sort_order' => $position,
            'is_active' => true,
        ]);
    });

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('openSortStudio')
        ->assertSet('showSortStudio', true)
        ->assertSet('sortingMode', true)
        ->call('reorderShortcut', $shortcuts[7]->id, $shortcuts[0]->id);

    expect($shortcuts[7]->fresh()->sort_order)->toBe(1);
    expect($shortcuts[0]->fresh()->sort_order)->toBe(2);

    $response = $this->get(route('home'));
    $content = $response->getContent();

    expect(strpos($content, 'Shortcut 8'))->toBeLessThan(strpos($content, 'Shortcut 1'));
});

test('sort studio can be opened and closed independently from the main list', function () {
    $user = User::factory()->manager()->create();

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->assertSet('showSortStudio', false)
        ->assertSet('sortingMode', false)
        ->call('openSortStudio')
        ->assertSet('showSortStudio', true)
        ->assertSet('sortingMode', true)
        ->call('closeSortStudio')
        ->assertSet('showSortStudio', false)
        ->assertSet('sortingMode', false);
});

test('reset seed order requires explicit confirmation first', function () {
    $user = User::factory()->manager()->create();

    $laravel = Shortcut::factory()->create([
        'title' => 'Laravel Documentation',
        'url' => 'https://laravel.com/docs',
        'sort_order' => 5,
    ]);
    $custom = Shortcut::factory()->create([
        'title' => 'Custom Alpha',
        'url' => 'https://example.com/alpha',
        'sort_order' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('openSortStudio')
        ->call('resetSeedOrder');

    expect($laravel->fresh()->sort_order)->toBe(5);
    expect($custom->fresh()->sort_order)->toBe(1);
});

test('reset seed order restores seeded shortcuts first and keeps custom shortcuts after them', function () {
    $user = User::factory()->manager()->create();

    $laravel = Shortcut::factory()->create([
        'title' => 'Laravel Documentation',
        'url' => 'https://laravel.com/docs',
        'sort_order' => 5,
    ]);
    $github = Shortcut::factory()->create([
        'title' => 'GitHub',
        'url' => 'https://github.com',
        'sort_order' => 3,
    ]);
    $notion = Shortcut::factory()->create([
        'title' => 'Notion Workspace',
        'url' => 'https://www.notion.so',
        'sort_order' => 7,
    ]);
    $customFirst = Shortcut::factory()->create([
        'title' => 'Custom Alpha',
        'url' => 'https://example.com/alpha',
        'sort_order' => 1,
    ]);
    $customSecond = Shortcut::factory()->create([
        'title' => 'Custom Beta',
        'url' => 'https://example.com/beta',
        'sort_order' => 4,
    ]);

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('openSortStudio')
        ->call('requestResetSeedOrder')
        ->assertSet('showResetOrderModal', true)
        ->call('resetSeedOrder');

    expect($laravel->fresh()->sort_order)->toBe(1);
    expect($github->fresh()->sort_order)->toBe(2);
    expect($notion->fresh()->sort_order)->toBe(3);
    expect($customFirst->fresh()->sort_order)->toBe(4);
    expect($customSecond->fresh()->sort_order)->toBe(5);

    $orderedUrls = Shortcut::query()
        ->orderBy('sort_order')
        ->pluck('url')
        ->all();

    expect($orderedUrls)->toBe([
        ShortcutSeeder::shortcuts()[0]['url'],
        ShortcutSeeder::shortcuts()[1]['url'],
        ShortcutSeeder::shortcuts()[2]['url'],
        'https://example.com/alpha',
        'https://example.com/beta',
    ]);
});

test('admin can create a pengelola account from the admin panel', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->set('managedUserName', 'Pengelola Shortcut')
        ->set('managedUserEmail', 'pengelola@example.com')
        ->set('managedUserPassword', 'password123')
        ->set('managedUserRole', User::ROLE_MANAGER)
        ->call('saveManagedUser');

    $managedUser = User::query()->where('email', 'pengelola@example.com')->first();

    expect($managedUser)->not->toBeNull();
    expect($managedUser->role)->toBe(User::ROLE_MANAGER);
    expect($managedUser->canAccessAdmin())->toBeTrue();
});

test('admin can update a management account from the admin panel', function () {
    $user = User::factory()->admin()->create();
    $managedUser = User::factory()->manager()->create([
        'name' => 'Pengelola Lama',
        'email' => 'lama@example.com',
        'password' => 'password123',
    ]);

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('editManagedUser', $managedUser->id)
        ->set('managedUserName', 'Pengelola Baru')
        ->set('managedUserEmail', 'baru@example.com')
        ->set('managedUserPassword', 'password456')
        ->set('managedUserRole', User::ROLE_ADMIN)
        ->call('saveManagedUser');

    $managedUser->refresh();

    expect($managedUser->name)->toBe('Pengelola Baru');
    expect($managedUser->email)->toBe('baru@example.com');
    expect($managedUser->role)->toBe(User::ROLE_ADMIN);
    expect(Hash::check('password456', $managedUser->password))->toBeTrue();
});

test('admin can delete a management account from the admin panel', function () {
    $user = User::factory()->admin()->create();
    $managedUser = User::factory()->manager()->create();

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('confirmManagedUserDelete', $managedUser->id)
        ->call('deleteManagedUser');

    expect(User::query()->find($managedUser->id))->toBeNull();
});

test('last admin cannot be downgraded from the admin panel', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->call('editManagedUser', $user->id)
        ->set('managedUserRole', User::ROLE_MANAGER)
        ->call('saveManagedUser')
        ->assertHasErrors(['managedUserRole']);

    expect($user->fresh()->role)->toBe(User::ROLE_ADMIN);
});

test('manager cannot create additional management accounts', function () {
    $user = User::factory()->manager()->create();

    $this->actingAs($user);

    Livewire::test(ShortcutAdminPanel::class)
        ->set('managedUserName', 'Admin Bayangan')
        ->set('managedUserEmail', 'bayangan@example.com')
        ->set('managedUserPassword', 'password123')
        ->set('managedUserRole', User::ROLE_MANAGER)
        ->call('saveManagedUser')
        ->assertForbidden();
});

test('viewers cannot access the admin panel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertForbidden();
});