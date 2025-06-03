<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReimbursementResource;
use App\Filament\Resources\ReimbursementResource\Widgets\ReimbursementStatWidget;
use App\Models\FollowupOfficer;
use App\Traits\FilamentListFunctions;
use Illuminate\Support\Facades\Auth;

class ListReimbursements extends ListRecords
{
    protected $routename = "reimbursements";
    use FilamentListFunctions;
    protected static string $resource = ReimbursementResource::class;
    protected function getHeaderWidgets():array
    {
        $reimbursementOfficer = FollowupOfficer::whereLike('action', 'reimbursement-%')->where('user_id', Auth::user()->id)->first();
        if($reimbursementOfficer) {
            return [
                ReimbursementStatWidget::class,
            ];
        }
        return [];
    }
}
