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
        FollowupOfficer::create(['action' => 'purchase-request-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'purchase-request-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'purchase-order-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'purchase-order-authorize ', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'item-request-issue', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'item-request-receive', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'item-receive-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'item-receive-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'item-receipt-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'stock-opname-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'delivery-order-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'delivery-order-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'return-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'return-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'business-trip-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'business-trip-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'leaves-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'leaves-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'overtime-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'overtime-acknowledge', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'reimbursement-approve', 'user_id' => null]);
        FollowupOfficer::create(['action' => 'reimbursement-acknowledge', 'user_id' => null]);
    }
}
