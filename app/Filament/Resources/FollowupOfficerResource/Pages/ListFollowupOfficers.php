<?php

namespace App\Filament\Resources\FollowupOfficerResource\Pages;

use App\Filament\Resources\FollowupOfficerResource;
use Filament\Resources\Pages\ListRecords;
use App\Traits\FilamentListFunctions;

class ListFollowupOfficers extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = FollowupOfficerResource::class;
}
