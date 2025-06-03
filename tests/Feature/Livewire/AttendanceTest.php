<?php

use App\Filament\Resources\AttendanceResource\Pages\CreateAttendance;
use App\Filament\Resources\AttendanceResource\Pages\EditAttendance;
use App\Filament\Resources\AttendanceResource\Pages\ListAttendances;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Privilege;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Schema;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->module = 'attendances';
    $this->module_singular = 'attendance';
    $this->class = Attendance::class;
    $this->listclass = ListAttendances::class;
    $this->createclass = CreateAttendance::class;
    $this->editclass = EditAttendance::class;

    Schema::disableForeignKeyConstraints();

    $this->menu = menu::where('url', $this->module)->first();
    $this->director = User::find(Employee::where('leader_user_id', 0)->first()->user_id);
    $this->owneruser = User::where([
        ['id', '!=', $this->director->id],
        ['id', '>', 1]
    ])->get()->random(1)->first();
    $this->authuser = User::where([
        ['id', '!=', $this->owneruser->id],
        ['id', '>', 1]
    ])->get()->random(1)->first();
    $this->otheruser = User::where([
        ['id', '>', 1], // Not Superuser
        ['id', '!=', $this->owneruser->id], // Not Owner User
        ['id', '!=', $this->authuser->id], // Not Auth User
        ['privilege_id', '!=', 6], // Not Admin,
        ['privilege_id', '!=', 3], // Not Director
    ])->get()->random(1)->first();
    $this->class::truncate();
    $this->foredit = $this->class::create([
        'user_id' => $this->owneruser->id,
        'tap_in' => fake()->dateTimeThisMonth(),
        'lat_in' => fake()->latitude(),
        'lon_in' => fake()->longitude(),
        'address_in' => fake()->address(),
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

it('can render page if user do not have list privileges', function () {
    Privilege::find(6)->update(['menu_ids' => '', 'privileges' => '']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    livewire($this->listclass)->assertSuccessful();
    livewire($this->listclass)->assertStatus(200);
});

it('can not render edit page', function () {
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => '', 'privileges' => '']);
    Livewire::actingAs($this->authuser)
        ->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(302)
        ->assertRedirect("room/{$this->module}");
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '15']);
    Livewire::actingAs($this->authuser)
        ->test($this->editclass, ['record' => $this->foredit->getRouteKey()])
        ->assertStatus(302)
        ->assertRedirect("room/{$this->module}");
});

it('can only show own attendance data if owner user login', function () {
    Livewire::actingAs($this->owneruser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(1);
    Livewire::actingAs($this->otheruser)
        ->test($this->listclass)
        ->assertCanNotSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(0);
});

it('can show all attendance data if presence-schedule-admin officer logged in', function () {
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'presence-schedule-admin', 'user_id' => $this->authuser->id]);
    Livewire::actingAs($this->owneruser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(1);
    Livewire::actingAs($this->authuser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(1);
    Livewire::actingAs($this->otheruser)
        ->test($this->listclass)
        ->assertCanNotSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(0);
});

it('can show all attendance data if director logged in', function () {
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'presence-schedule-admin', 'user_id' => $this->authuser->id]);
    Livewire::actingAs($this->owneruser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(1);
    Livewire::actingAs($this->director)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(1);
    Livewire::actingAs($this->otheruser)
        ->test($this->listclass)
        ->assertCanNotSeeTableRecords(Attendance::all())
        ->assertCountTableRecords(0);
});

it('can not filter user if not presence-schedule-admin officer nor director logged in', function () {
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    $response = $this->actingAs($this->owneruser)->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())->not->toContain('tableFilters.user_id.value');
});

it('can filter user if presence-schedule-admin officer or director logged in', function () {
    FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'presence-schedule-admin', 'user_id' => $this->authuser->id]);

    $response = $this->actingAs($this->authuser)->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())->toContain('tableFilters.user_id.value');

    $response = $this->actingAs($this->director)->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())->toContain('tableFilters.user_id.value');
});

it('can redirect to tap page if click New Attendance', function () {
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => '', 'privileges' => '']);
    Livewire::actingAs($this->authuser)
        ->test($this->createclass)
        ->assertStatus(302)
        ->assertRedirect("room/{$this->module}/tap");
    Privilege::find($this->authuser->privilege_id)->update(['menu_ids' => $this->menu->id, 'privileges' => '15']);
    Livewire::actingAs($this->authuser)
        ->test($this->createclass)
        ->assertStatus(302)
        ->assertRedirect("room/{$this->module}/tap");
});

it('can tap attendance', function () {})->only();

it('can not see delete icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('delete");
});

it('can not see view icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '0']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->not->toContain("mountTableAction('view");
});
