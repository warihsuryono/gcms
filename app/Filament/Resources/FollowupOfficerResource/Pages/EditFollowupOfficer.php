<?php

namespace App\Filament\Resources\FollowupOfficerResource\Pages;

use App\Filament\Resources\FollowupOfficerResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\FilamentEditFunctions;

class EditFollowupOfficer extends EditRecord
{
    protected $routename = 'followup-officers';
    use FilamentEditFunctions;
    protected static string $resource = FollowupOfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
