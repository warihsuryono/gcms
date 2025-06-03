<?php

use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Privilege;
use Illuminate\Http\UploadedFile;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;

beforeEach(function () {
    $this->module = 'users';
    $this->module_singular = 'user';
    $this->class = User::class;
    $this->listclass = ListUsers::class;
    $this->createclass = CreateUser::class;
    $this->editclass = EditUser::class;
});

it('can redirect login page while user not login', function () {
    $response = $this->get('room');
    $response->assertStatus(302);
    $response->assertRedirect('room/login');
});

it('can login and redirect to home', function () {
    $response = $this->actingAs(User::find(1))->withSession(['banned' => false])->get('room/login');
    $response->assertStatus(302);
});

it('can render page', function () {
    $response = $this->actingAs(User::find(1))->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    livewire($this->listclass)->assertSuccessful();
    livewire($this->listclass)->assertStatus(200);
});

it('can not render page if user do not have list privileges', function () {
    Privilege::find(6)->update(['menu_ids' => '', 'privileges' => '']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(302);
    $response->assertRedirect('room');
    livewire($this->listclass)->assertStatus(302);
});

it('can render page but can not render add page if user do not have add privileges', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/create");
    $response->assertStatus(302);
    $response->assertRedirect("room/{$this->module}");
    livewire($this->createclass)->assertStatus(302);
});

it('can render add page if user have add privileges', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    $response->assertSee("New {$this->module_singular}");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/create");
    $response->assertStatus(200);
    $response->assertSee(ucfirst($this->module_singular) . ' Details');
});

it("can save new data", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $user = User::factory()->make()->toArray();
    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->createclass)
        ->fillForm([
            'privilege_id' => 6,
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => bcrypt('password'),
            'msisdn' => $user['msisdn'],
        ])
        ->call('create')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'email' => $user['email'],
        'name' => $user['name'],
        'msisdn' => $user['msisdn'],
    ]);
});

it('can upload new signature & photo', function () {
    $filesignature = UploadedFile::fake()->image('signature.png');
    $filephoto = UploadedFile::fake()->image('photo.png');
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $user = User::factory()->make()->toArray();
    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->createclass)
        ->set('data.signature', $filesignature)
        ->set('data.photo', $filephoto)
        ->fillForm([
            'privilege_id' => 6,
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => bcrypt('password'),
            'msisdn' => $user['msisdn'],
        ])
        ->call('create')->assertHasNoErrors();
    $user = User::all()->last();
    expect(Storage::exists('public/' . $user->signature))->toBe(true);
    expect(Storage::exists('public/' . $user->photo))->toBe(true);
});

it('can render page but can not render edit page if user do not have edit privileges', function () {
    $foredit = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/{$foredit->id}/edit");
    $response->assertStatus(302);
    $response->assertRedirect("room/{$this->module}");
    livewire($this->createclass)->assertStatus(302);
});

it('can render edit page if user have edit privileges', function () {
    $foredit = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain("room/{$this->module}/{$foredit->id}/edit")
        ->not->toContain("New {$this->module_singular}");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/{$foredit->id}/edit");
    $response->assertStatus(200);
    $response->assertSee('Edit ' . ucfirst($this->module_singular));
});

it('can edit data', function () {
    $user = User::where('id', '>', 1)->first();
    $rollback_user_name = $user->name;
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->editclass, ['record' => $user->getRouteKey()])
        ->fillForm(['name' => $user->name . " edited"])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'email' => $user->email,
        'name' => $rollback_user_name . " edited",
        'msisdn' => $user->msisdn,
    ]);

    User::where('id', '>', 1)->first()->update(['name' => $rollback_user_name]);
});

it('can change password', function () {
    $user = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    $old_password = $user->password;
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->editclass, ['record' => $user->getRouteKey()])
        ->fillForm([
            'new_password' => 'password_edited',
            'new_password_confirmation' => 'password_edited'
        ])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'email' => $user->email,
        'name' => $user->name,
        'msisdn' => $user->msisdn
    ]);

    expect($old_password != User::where('id', '>', 1)->first()->password)->toBe(true);

    User::where('id', '>', 1)->first()->update(['password' => $old_password]);
});

it('can edit signature & photo', function () {
    $user = User::where('id', '>', 1)->first();
    $filesignature = UploadedFile::fake()->image('signature_' . rand(0, 1000) . '.png');
    $filephoto = UploadedFile::fake()->image('photo_' . rand(0, 1000) . '.png');
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);
    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->editclass, ['record' => $user->getRouteKey()])
        ->set('data.signature', $filesignature)
        ->set('data.photo', $filephoto)
        ->call('save')->assertHasNoErrors();
    $user = User::where('id', '>', 1)->first();
    expect(Storage::exists('public/' . $user->signature))->toBe(true);
    expect(Storage::exists('public/' . $user->photo))->toBe(true);
});

it('can not see delete icon', function () {
    $user = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('delete', '" . $user->id . "')");
});

it('can see delete icon', function () {
    $user = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '8']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain("mountTableAction('delete', '" . $user->id . "')");
});

it('can not see view icon', function () {
    $user = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('view', '" . $user->id . "')");
});

it('can see view icon', function () {
    $user = User::where('id', '>', 1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '4']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain("mountTableAction('view', '" . $user->id . "')");
});
