<?php

use App\Filament\Resources\PresenceScheduleResource\Pages\CreatePresenceSchedule;
use App\Filament\Resources\PresenceScheduleResource\Pages\EditPresenceSchedule;
use App\Filament\Resources\PresenceScheduleResource\Pages\ListPresenceSchedules;
use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\FollowupOfficer;
use App\Models\PresenceSchedule;
use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Privilege;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->module = 'presence-schedules';
    $this->module_singular = 'presence-schedule';
    $this->class = PresenceSchedule::class;
    $this->listclass = ListPresenceSchedules::class;
    $this->createclass = CreatePresenceSchedule::class;
    $this->editclass = EditPresenceSchedule::class;
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
    if (!$menu) $menu = menu::create(['seqno' => 1, 'parent_id' => 2, 'name' => 'Presence Schedules', 'url' => 'presence-schedules']);
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
    $response->assertSee("New presence schedule");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/create");
    $response->assertStatus(200);
    $response->assertSee('Create Presence Schedule');
});

it("can choosing user id if auth is presence-schedule-admin", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $authuser = User::where('privilege_id', 6)->first();
    $randomuser = User::where('id', '!=', $authuser->id)->get()->random(1)->first();
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'presence-schedule-admin', 'user_id' => $authuser->id]);
    Livewire::actingAs($authuser)
        ->test($this->createclass)
        ->assertFormComponentExists('data.user_id')
        ->assertFormComponentExists('data.activity_id')
        ->fillForm([
            'user_id' => $randomuser->id,
            'presence_at' => fake('id')->dateTimeThisMonth(),
            'hour_from' => '08:00',
            'hour_until' => '17:00',
            'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
            'notes' => fake('id')->sentence(3),
        ])
        ->call('create')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, ['user_id' => $this->class::all()->last()->user_id]);
    expect($this->class::all()->last()->user_id == $randomuser->id)->toBe(true);
    User::where('id', '>', 10)->forceDelete();
    $this->class::where('id', '>', 0)->forceDelete();
});

it("can not choosing user id if auth is not presence-schedule-admin & saving with auth user_id", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $authuser = User::where('privilege_id', 6)->first();
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    Livewire::actingAs($authuser)
        ->test($this->createclass)
        ->assertFormComponentDoesNotExist('data.user_id')
        ->assertFormComponentExists('data.activity_id')
        ->fillForm([
            'presence_at' => fake('id')->dateTimeThisMonth(),
            'hour_from' => '08:00',
            'hour_until' => '17:00',
            'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
            'notes' => fake('id')->sentence(3),
        ])
        ->call('create')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, ['user_id' => $this->class::all()->last()->user_id]);
    expect($this->class::all()->last()->user_id == $authuser->id)->toBe(true);
    User::where('id', '>', 10)->forceDelete();
});

it("can show all user if auth is presence-schedule-admin or directors", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    Privilege::find(3)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $authuser = User::where('privilege_id', 6)->first();
    $director = User::find(Employee::where('leader_user_id', 0)->first()->user_id);
    $otheruser = User::where('id', '!=', $authuser->id)->get()->random(1)->first();
    $this->class::where('id', '>', 0)->forceDelete();
    PresenceSchedule::create([
        'user_id' => $otheruser->id,
        'presence_at' => fake('id')->dateTimeThisMonth(),
        'hour_from' => '08:00',
        'hour_until' => '17:00',
        'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
        'notes' => fake('id')->sentence(3),
    ]);
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'presence-schedule-admin', 'user_id' => $authuser->id]);
    Livewire::actingAs($authuser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(PresenceSchedule::all())
        ->assertCountTableRecords(1);

    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    Livewire::actingAs($director)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(PresenceSchedule::all())
        ->assertCountTableRecords(1);

    $this->class::where('id', '>', 0)->forceDelete();
});

it("can not show all user but only auth user if auth is not presence-schedule-admin nor directors", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $authuser = User::where('privilege_id', 6)->first();
    $otheruser = User::where('id', '!=', $authuser->id)->get()->random(1)->first();
    $this->class::where('id', '>', 0)->forceDelete();
    PresenceSchedule::create([
        'user_id' => $otheruser->id,
        'presence_at' => fake('id')->dateTimeThisMonth(),
        'hour_from' => '08:00',
        'hour_until' => '17:00',
        'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
        'notes' => fake('id')->sentence(3),
    ]);
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    Livewire::actingAs($authuser)
        ->test($this->listclass)
        ->assertCanNotSeeTableRecords(PresenceSchedule::all())
        ->assertCountTableRecords(0);

    $this->class::where('id', '>', 0)->forceDelete();
});

it("can save new data", function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->createclass)
        ->fillForm([
            'user_id' => User::all()->random(1)->first()->id,
            'presence_at' => fake('id')->dateTimeThisMonth(),
            'hour_from' => '08:00',
            'hour_until' => '17:00',
            'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
            'notes' => fake('id')->sentence(3),
        ])
        ->call('create')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'presence_at' => $this->class::all()->last()->presence_at,
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
    $response->assertSee('Edit Presence Schedule');
});

// it('can only show edit page its own data', function () {});

it('can edit data', function () {
    $foredit = $this->class::all()->random(1)->first();
    $foredit_id = $foredit->id;
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->editclass, ['record' => $foredit->getRouteKey()])
        ->fillForm(['presence_at' => fake('id')->dateTimeThisMonth()])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'presence_at' => $this->class::find($foredit_id)->presence_at,
    ]);
});

it('can not see delete icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('delete");
});

it('can see delete icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '8']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain("mountTableAction('delete");
});

it('can not see view icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('view");
});

it('can see view icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '4']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain("mountTableAction('view");
});
