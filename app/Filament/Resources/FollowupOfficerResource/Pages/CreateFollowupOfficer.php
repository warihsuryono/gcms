<?php

namespace App\Filament\Resources\FollowupOfficerResource\Pages;

use App\Filament\Resources\FollowupOfficerResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentCreateFunctions;

class CreateFollowupOfficer extends CreateRecord
{
    protected $routename = 'followup-officers';
    use FilamentCreateFunctions;
    protected static string $resource = FollowupOfficerResource::class;
    protected static bool $canCreateAnother = false;
}
