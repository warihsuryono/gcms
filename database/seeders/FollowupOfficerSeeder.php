<?php

namespace Database\Seeders;

use App\Models\FollowupOfficer;
use Illuminate\Database\Seeder;

class FollowupOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FollowupOfficer::create(['action' => 'item-request-issue', 'user_id' => 6]);
        FollowupOfficer::create(['action' => 'purchase-order-approve', 'user_id' => 6]);
        FollowupOfficer::create(['action' => 'purchase-order-authorize', 'user_id' => 4]);
        FollowupOfficer::create(['action' => 'item-receipt-approve', 'user_id' => 6]);
        FollowupOfficer::create(['action' => 'stock-opname-approve', 'user_id' => 6]);
        FollowupOfficer::create(['action' => 'stock-keeper', 'user_id' => 6]);
    }
}
