<?php

namespace App\Filament\Resources\ReimbursementResource\Widgets;

use App\Models\Reimbursement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReimbursementStatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $paid = Reimbursement::where('is_paid', 1)->count();
        $unpaid = Reimbursement::where('is_paid', 0)->count();
        $unpaid = Reimbursement::where('is_paid', 0)->count();
        $unpaidApproved = Reimbursement::where([
            ['is_paid', 0],
            ['is_approved', 1]
        ])->count();
        return [
            Stat::make('Paid', number_format($paid))
                ->description("Paid reimbursements")
                ->color('success'),
            Stat::make('Unpaid', number_format($unpaid))
                ->description("Unpaid reimbursements")
                ->color('danger'),
            Stat::make('Approved', number_format($unpaidApproved))
                ->description("Reimbursements approved and unpaid")
                ->color('primary'),
        ];
    }
}
