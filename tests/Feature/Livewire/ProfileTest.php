<?php

use App\Filament\Resources\ProfileResource\Pages\CreateProfile;
use App\Filament\Resources\ProfileResource\Pages\EditProfile;
use App\Filament\Resources\ProfileResource\Pages\ListProfiles;
use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Privilege;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->module = 'profiles';
    $this->module_singular = 'profile';
    $this->class = User::class;
    $this->listclass = ListProfiles::class;
    $this->createclass = CreateProfile::class;
    $this->editclass = EditProfile::class;
});

it('can render page', function () {
    $authuser = User::all()->random(1)->first();
    $response = $this->actingAs($authuser)->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(302);
    $response->assertRedirect("room/{$this->module}/{$authuser->id}/edit");
});

it('redirect edit profile page if force to create page', function () {
    $authuser = User::all()->random(1)->first();
    $response = $this->actingAs($authuser)->withSession(['banned' => false])->get("room/{$this->module}/create");
    $response->assertStatus(302);
    $response->assertRedirect("room/{$this->module}");
});

it('redirect auth user profile page if force to other user profile', function () {
    $authuser = User::all()->random(1)->first();
    $otheruser = User::where('id', '<>', $authuser->id)->first();
    $response = $this->actingAs($authuser)->withSession(['banned' => false])->get("room/{$this->module}/{$otheruser->id}/edit");
    $response->assertStatus(302);
    $response->assertRedirect("room/{$this->module}");
});

it('can edit data', function () {
    $authuser = User::all()->random(1)->first();
    $rollback_user_id = $authuser->id;
    $rollback_user_name = $authuser->name;
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs($authuser)
        ->test($this->editclass, ['record' => $authuser->getRouteKey()])
        ->fillForm(['name' => $authuser->name . " edited"])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'email' => $authuser->email,
        'name' => $rollback_user_name . " edited",
        'msisdn' => $authuser->msisdn,
    ]);

    User::find($rollback_user_id)->update(['name' => $rollback_user_name]);
});

it('can change password', function () {
    $authuser = User::all()->random(1)->first();
    $rollback_user_id = $authuser->id;
    $old_password = $authuser->password;
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs($authuser)
        ->test($this->editclass, ['record' => $authuser->getRouteKey()])
        ->fillForm([
            'new_password' => 'password_edited',
            'new_password_confirmation' => 'password_edited'
        ])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'email' => $authuser->email,
        'name' => $authuser->name,
        'msisdn' => $authuser->msisdn
    ]);

    expect($old_password != User::find($rollback_user_id)->password)->toBe(true);

    User::find($rollback_user_id)->update(['password' => bcrypt('password')]);
});

it('can edit signature & photo', function () {
    $authuser = User::all()->random(1)->first();
    $filesignature = UploadedFile::fake()->image('signature_' . rand(0, 1000) . '.png');
    $filephoto = UploadedFile::fake()->image('photo_' . rand(0, 1000) . '.png');
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);
    Livewire::actingAs($authuser)
        ->test($this->editclass, ['record' => $authuser->getRouteKey()])
        ->set('data.signature', $filesignature)
        ->set('data.photo', $filephoto)
        ->call('save')->assertHasNoErrors();
    $authuser = User::find($authuser->id);
    expect(Storage::exists('public/' . $authuser->signature))->toBe(true);
    expect(Storage::exists('public/' . $authuser->photo))->toBe(true);
});
