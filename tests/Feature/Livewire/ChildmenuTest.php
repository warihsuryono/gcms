<?php

use App\Filament\Resources\ChildmenuResource\Pages\CreateChildmenu;
use App\Filament\Resources\ChildmenuResource\Pages\EditChildmenu;
use App\Filament\Resources\ChildmenuResource\Pages\ListChildmenus;
use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Privilege;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->module = 'menus';
    $this->module_singular = 'menu';
    $this->class = menu::class;
    $this->listclass = ListChildmenus::class;
    $this->createclass = CreateChildmenu::class;
    $this->editclass = EditChildmenu::class;
});

it('can render page', function () {
    $response = $this->actingAs(User::find(1))->withSession(['banned' => false])->get("room/child{$this->module}");
    $response->assertStatus(200);
    livewire($this->listclass)->assertSuccessful();
    livewire($this->listclass)->assertStatus(200);
});

it('can not render page if user do not have list privileges', function () {
    Privilege::find(6)->update(['menu_ids' => '', 'privileges' => '']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/child{$this->module}");
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
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/child{$this->module}");
    $response->assertStatus(200);
    $response->assertSee("Add Child Menu");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/child{$this->module}/create");
    $response->assertStatus(200);
    $response->assertSee('Create Child Menu');
});

it("can save new data", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->createclass)
        ->fillForm([
            'parent_id' => 1,
            'url' => fake('id')->name(),
            'route' => fake('id')->name(),
            'name' => fake('id')->name()
        ])
        ->call('create')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'name' => $this->class::all()->last()->name,
    ]);
});

it('can render page but can not render edit page if user do not have edit privileges', function () {
    $foredit = $this->class::all()->random(1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/{$foredit->id}/edit");
    $response->assertStatus(302);
    $response->assertRedirect("room/{$this->module}");
    livewire($this->createclass)->assertStatus(302);
});

it('can render edit page if user have edit privileges', function () {
    $foredit = $this->class::all()->random(1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain(env('APP_URL') . "/room/{$this->module}/")->toContain('/edit"')
        ->not->toContain("New {$this->module_singular}");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/{$foredit->id}/edit");
    $response->assertStatus(200);
    $response->assertSee('Edit ' . ucfirst($this->module_singular));
});

it('can edit data', function () {
    $foredit = $this->class::all()->random(1)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->editclass, ['record' => $foredit->getRouteKey()])
        ->fillForm(['name' => fake('id')->name()])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'name' => $this->class::all()->last()->name,
    ]);
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
