<?php

use App\Models\Bank;
use App\Models\City;
use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Employee;
use App\Models\Province;
use App\Models\Privilege;
use App\Models\BusinessTrip;
use App\Models\FollowupOfficer;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Schema;
use Filament\Notifications\Notification;
use App\Filament\Resources\BusinessTripResource\Pages\EditBusinessTrip;
use App\Filament\Resources\BusinessTripResource\Pages\ListBusinessTrips;
use App\Filament\Resources\BusinessTripResource\Pages\CreateBusinessTrip;
use App\Filament\Resources\BusinessTripResource\Pages\ViewBusinessTrip;

beforeEach(function () {
    $this->module = 'business-trips';
    $this->module_singular = 'business-trip';
    $this->class = BusinessTrip::class;
    $this->listclass = ListBusinessTrips::class;
    $this->createclass = CreateBusinessTrip::class;
    $this->editclass = EditBusinessTrip::class;
    $this->viewclass = ViewBusinessTrip::class;

    Schema::disableForeignKeyConstraints();

    $this->menu = menu::where('url', $this->module)->first();
    $this->owneruser = User::all()->random(1)->first();
    $this->authuser = User::where('id', '!=', $this->owneruser->id)->get()->random(1)->first();
    $this->otheruser = User::where('id', '!=', $this->owneruser->id)->where('id', '!=', $this->authuser->id)->get()->random(1)->first();
    $this->director = User::find(Employee::where('leader_user_id', 0)->first()->user_id);
    $this->class::truncate();
    $this->foredit = $this->class::create([
        'doc_no' => fake('id')->numerify('BTR-#####'),
        'province_id' => Province::all()->random(1)->first()->id,
        'city_id' => City::all()->random(1)->first()->id,
        'destination' => fake('id')->sentence(2),
        'airport_destination' => fake('id')->sentence(2),
        'departure_at' => fake()->dateTimeThisMonth(),
        'arrival_at' => fake()->dateTimeThisMonth(),
        'project_name' => fake('id')->sentence(2),
        'bank_id' => Bank::all()->random(1)->first()->id,
        'bank_account_name' => fake('id')->name(),
        'bank_account_no' => fake('id')->randomNumber(6),
        'total' => fake()->randomNumber(6),
        'created_by' => $this->owneruser->id,
    ]);
});

afterAll(function () {
    Schema::enableForeignKeyConstraints();
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
    livewire($this->listclass)->assertStatus(302)->assertNotified('Sorry, you don`t have the privilege!');
});

it('can render page but can not render add page if user do not have add privileges', function () {
    $menu = menu::where('url', $this->module)->first();
    if (!$menu) $menu = menu::create(['seqno' => 1, 'parent_id' => 2, 'name' => 'Business Trips', 'url' => 'business-trips']);
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
    $response->assertSee("New business trip");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/create");
    $response->assertStatus(200);
    $response->assertSee('Create Business Trip');
});

it("can save new data", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->createclass)
        ->fillForm([
            'doc_no' => fake('id')->numerify('BTR-#####'),
            'province_id' => Province::all()->random(1)->first()->id,
            'city_id' => City::all()->random(1)->first()->id,
            'destination' => fake('id')->sentence(2),
            'airport_destination' => fake('id')->sentence(2),
            'departure_at' => fake()->dateTimeThisMonth(),
            'arrival_at' => fake()->dateTimeThisMonth(),
            'project_name' => fake('id')->sentence(2),
            'bank_id' => Bank::all()->random(1)->first()->id,
            'bank_account_name' => fake('id')->name(),
            'bank_account_no' => fake('id')->randomNumber(6),
            'total' => fake()->randomNumber(6),
        ])
        ->call('create')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'project_name' => $this->class::all()->last()->project_name,
    ]);

    $this->class::all()->last()->forceDelete();
});

it("can show all user if auth is business-trip-admin or directors", function () {
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '1']);
    Privilege::find(3)->update(['menu_ids' => $this->menu->id, 'privileges' => '1']);

    FollowupOfficer::where('action', 'business-trip-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'business-trip-admin', 'user_id' => $this->authuser->id]);

    Livewire::actingAs($this->authuser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords($this->class::all())
        ->assertCountTableRecords(1);

    FollowupOfficer::where('action', 'business-trip-admin')->forceDelete();
    Livewire::actingAs($this->director)
        ->test($this->listclass)
        ->assertCanSeeTableRecords($this->class::all())
        ->assertCountTableRecords(1);
});

// it("can not show all user but only auth user if auth is not business-trip-admin nor directors", function () {
//     Privilege::find($this->owneruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '1']);

//     FollowupOfficer::where('action', 'business-trip-admin')->forceDelete();

//     Livewire::actingAs($this->otheruser)->test($this->listclass)
//         ->assertCanNotSeeTableRecords($this->class::all())
//         ->assertCountTableRecords(0);

//     Livewire::actingAs($this->owneruser)->test($this->listclass)
//         ->assertCanSeeTableRecords($this->class::all())
//         ->assertCountTableRecords(1);

//     Livewire::actingAs($this->director)->test($this->listclass)
//         ->assertCanSeeTableRecords($this->class::all())
//         ->assertCountTableRecords(1);
// });

it('can render page but can not render edit page if user do not have edit privileges', function () {
    Privilege::find($this->owneruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '0']);
    Livewire::actingAs($this->owneruser)->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(302)
        ->assertSessionHas('_flash')
        ->assertNotified(Notification::make()->title('Sorry, you don`t have the privilege!')->warning())
        ->assertRedirect("room/{$this->module}");
});

it('can render edit page if user have edit privileges, the owner', function () {
    Privilege::find($this->owneruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '2']);
    Privilege::find($this->otheruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '2']);

    Livewire::actingAs($this->otheruser)->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(302)
        ->assertRedirect("room/{$this->module}")
        ->assertNotified('Sorry, you don`t have the privilege!');

    Livewire::actingAs($this->owneruser)->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(200)
        ->assertSee('Edit Business Trip');
});

it('cannot edit even auth is business-trip-admin', function () {
    Privilege::find($this->owneruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '2']);
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '2']);

    FollowupOfficer::where('action', 'business-trip-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'business-trip-admin', 'user_id' => $this->authuser->id]);

    Livewire::actingAs($this->authuser)->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(302)
        ->assertRedirect("room/{$this->module}")
        ->assertNotified('Sorry, you don`t have the privilege!');

    Livewire::actingAs($this->owneruser)->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(200)
        ->assertSee('Edit Business Trip');
});

it('can edit data if user have edit privileges, the owner', function () {
    Privilege::find($this->owneruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '2']);

    Livewire::actingAs($this->owneruser)
        ->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->fillForm([
            'leader_id' => Null,
            'team1_id' => Null,
            'team2_id' => Null,
            'team3_id' => Null,
            'project_name' => fake('id')->sentence(2)
        ])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'project_name' => $this->class::find($this->foredit->id)->project_name,
    ]);
});

it('can approve if auth is business-trip-approve', function () {
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '4']);
    Privilege::find($this->owneruser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '4']);

    FollowupOfficer::where('action', 'business-trip-approve')->forceDelete();
    FollowupOfficer::create(['action' => 'business-trip-approve', 'user_id' => $this->authuser->id]);

    Livewire::actingAs($this->otheruser)->test($this->viewclass, ['record' => $this->foredit->getRouteKey()])
        ->assertRedirect("room/{$this->module}");

    Livewire::actingAs($this->owneruser)->test($this->viewclass, ['record' => $this->foredit->getRouteKey()])
        ->assertSuccessful()
        ->assertDontSee("approve({$this->foredit->id})");

    Livewire::actingAs($this->authuser)->test($this->viewclass, ['record' => $this->foredit->getRouteKey()])
        ->assertSuccessful()
        ->assertSee("approve({$this->foredit->id})");
});

// it('can acknowledge if auth is business-trip-acknowledge', function () {})->only();

// it('cannot payment and edit even auth is business-trip-payment before approve', function () {});

// it('can payment but not edit if auth is business-trip-payment after approve', function () {});

it('can not see delete icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('delete");
});

// it('can see delete icon', function () {
//     $menu = menu::where('url', $this->module)->first();
//     Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '8']);
//     $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
//     $response->assertStatus(200);
//     expect($response->content())
//         ->toContain("mountTableAction('delete");
// })->only();

it('can not see view icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('view");
});

// it('can see view icon', function () {
//     $menu = menu::where('url', $this->module)->first();
//     Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '4']);
//     $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
//     $response->assertStatus(200);
//     expect($response->content())
//         ->toContain("mountTableAction('view");
// })->only();
