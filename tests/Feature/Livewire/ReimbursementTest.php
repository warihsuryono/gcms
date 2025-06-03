<?php

use App\Filament\Resources\ReimbursementResource\Pages\CreateReimbursement;
use App\Filament\Resources\ReimbursementResource\Pages\EditReimbursement;
use App\Filament\Resources\ReimbursementResource\Pages\ListReimbursements;
use App\Models\Bank;
use App\Models\Employee;
use App\Models\FollowupOfficer;
use App\Models\menu;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Privilege;
use App\Models\Reimbursement;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->module = 'reimbursements';
    $this->module_singular = 'reimbursement';
    $this->class = Reimbursement::class;
    $this->listclass = ListReimbursements::class;
    $this->createclass = CreateReimbursement::class;
    $this->editclass = EditReimbursement::class;
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
    if (!$menu) $menu = menu::create(['seqno' => 1, 'parent_id' => 2, 'name' => 'Reimburements', 'url' => 'reimbursements']);
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
    $response->assertSee("New reimbursement");
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}/create");
    $response->assertStatus(200);
    $response->assertSee('Create Reimbursement');
});

// it("can choosing user id if auth is presence-schedule-admin", function () {
//     $menu = menu::where('url', $this->module)->first();
//     Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
//     $authuser = User::where('privilege_id', 6)->first();
//     $randomuser = User::where('id', '!=', $authuser->id)->get()->random(1)->first();
//     FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
//     FollowupOfficer::create(['action' => 'presence-schedule-admin', 'user_id' => $authuser->id]);
//     Livewire::actingAs($authuser)
//         ->test($this->createclass)
//         ->assertFormComponentExists('data.user_id')
//         ->assertFormComponentExists('data.activity_id')
//         ->fillForm([
//             'user_id' => $randomuser->id,
//             'presence_at' => fake('id')->dateTimeThisMonth(),
//             'hour_from' => '08:00',
//             'hour_until' => '17:00',
//             'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
//             'notes' => fake('id')->sentence(3),
//         ])
//         ->call('create')->assertHasNoErrors();

//     $this->assertDatabaseHas($this->class, ['user_id' => $this->class::all()->last()->user_id]);
//     expect($this->class::all()->last()->user_id == $randomuser->id)->toBe(true);
//     User::where('id', '>', 10)->forceDelete();
//     $this->class::where('id', '>', 0)->forceDelete();
// });

// it("can not choosing user id if auth is not presence-schedule-admin & saving with auth user_id", function () {
//     $menu = menu::where('url', $this->module)->first();
//     Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
//     $authuser = User::where('privilege_id', 6)->first();
//     FollowupOfficer::where('action', 'presence-schedule-admin')->forceDelete();
//     Livewire::actingAs($authuser)
//         ->test($this->createclass)
//         ->assertFormComponentDoesNotExist('data.user_id')
//         ->assertFormComponentExists('data.activity_id')
//         ->fillForm([
//             'presence_at' => fake('id')->dateTimeThisMonth(),
//             'hour_from' => '08:00',
//             'hour_until' => '17:00',
//             'activity_id' => EmployeeActivity::all()->random(1)->first()->id,
//             'notes' => fake('id')->sentence(3),
//         ])
//         ->call('create')->assertHasNoErrors();

//     $this->assertDatabaseHas($this->class, ['user_id' => $this->class::all()->last()->user_id]);
//     expect($this->class::all()->last()->user_id == $authuser->id)->toBe(true);
//     User::where('id', '>', 10)->forceDelete();
// });

it("can show all user if auth is reimbursement-admin or directors", function () {
    Schema::disableForeignKeyConstraints();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    Privilege::find(3)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $authuser = User::where('privilege_id', 6)->first();
    $director = User::find(Employee::where('leader_user_id', 0)->first()->user_id);
    $otheruser = User::where('id', '!=', $authuser->id)->get()->random(1)->first();
    $this->class::where('id', '>', 0)->forceDelete();
    Reimbursement::create([
        'user_id' => $otheruser->id,
        'bank_id' => Bank::all()->random(1)->first()->id,
        'bank_account_name' => $otheruser->name,
        'bank_account_no' => fake('id')->numberBetween(1000000000, 9999999999),
        'notes' => fake('id')->sentence(3),
    ]);
    FollowupOfficer::where('action', 'reimbursement-admin')->forceDelete();
    FollowupOfficer::create(['action' => 'reimbursement-admin', 'user_id' => $authuser->id]);
    Livewire::actingAs($authuser)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Reimbursement::all())
        ->assertCountTableRecords(1);

    FollowupOfficer::where('action', 'reimbursement-admin')->forceDelete();
    Livewire::actingAs($director)
        ->test($this->listclass)
        ->assertCanSeeTableRecords(Reimbursement::all())
        ->assertCountTableRecords(1);
    $this->class::where('id', '>', 0)->forceDelete();
    Schema::enableForeignKeyConstraints();
});

it("can not show all user but only auth user if auth is not reimbursement-admin nor directors", function () {
    Schema::disableForeignKeyConstraints();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $authuser = User::where('privilege_id', 6)->first();
    $otheruser = User::where('id', '!=', $authuser->id)->get()->random(1)->first();
    $this->class::where('id', '>', 0)->forceDelete();
    Reimbursement::create([
        'user_id' => $otheruser->id,
        'bank_id' => Bank::all()->random(1)->first()->id,
        'bank_account_name' => $otheruser->name,
        'bank_account_no' => fake('id')->numberBetween(1000000000, 9999999999),
        'notes' => fake('id')->sentence(3),
    ]);
    FollowupOfficer::where('action', 'reimbursement-admin')->forceDelete();
    Livewire::actingAs($authuser)
        ->test($this->listclass)
        ->assertCanNotSeeTableRecords(Reimbursement::all())
        ->assertCountTableRecords(0);

    $this->class::where('id', '>', 0)->forceDelete();
    Schema::enableForeignKeyConstraints();
});

it("can save new data", function () {
    $user = User::where('privilege_id', 6)->first();
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '1']);
    $data = [
        'bank_id' => Bank::all()->random(1)->first()->id,
        'bank_account_name' => fake('id')->name(),
        'bank_account_no' => fake('id')->numberBetween(1000000000, 9999999999),
        'notes' => fake('id')->sentence(3),
    ];

    // Select User Id from Followup Officer who have action = 'reimbursement-approve'
    $userIds = FollowupOfficer::where('action','reimbursement-approve')->get()->pluck('user_id')->toArray(); //returning Array
    // Select Users 
    $users = User::whereIn('id', $userIds)->get();
    $me = $user;

    Livewire::actingAs($user)
    ->test($this->createclass)
    ->set('data.bank_id', $data['bank_id'])
    ->set('data.bank_account_name', $data['bank_account_name'])
    ->set('data.bank_account_no', $data['bank_account_no'])
    ->set('data.notes', $data['notes'])
    ->call('create')
    ->assertHasNoFormErrors([
        'bank_id',
        'bank_account_name',
        'bank_account_no',
        'notes',
    ])->assertNotified('Created');
   
    $this->assertDatabaseHas($this->class, $data);
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
    $response->assertSee('Edit Reimbursement');
});

// it('can only show edit page its own data', function () {});

it('can edit data', function () {
    $foredit = $this->class::all()->random(1)->first();
    $foredit_id = $foredit->id;
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '2']);

    Livewire::actingAs(User::where('privilege_id', 6)->first())
        ->test($this->editclass, ['record' => $foredit->getRouteKey()])
        ->fillForm(['bank_account_name' => fake('id')->name()])
        ->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas($this->class, [
        'bank_account_name' => $this->class::find($foredit_id)->bank_account_name,
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
        ->not->toContain('title="View"');
});

it('can see view icon', function () {
    $menu = menu::where('url', $this->module)->first();
    Privilege::find(6)->update(['menu_ids' => $menu->id, 'privileges' => '4']);
    $response = $this->actingAs(User::where('privilege_id', 6)->first())->withSession(['banned' => false])->get("room/{$this->module}");
    $response->assertStatus(200);
    expect($response->content())
        ->toContain('title="View"');
});
