<?php

namespace App\Filament\Resources\PrivilegeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PrivilegeResource;
use App\Traits\FilamentCreateFunctions;

class CreatePrivilege extends CreateRecord
{
    protected $routename = 'privileges';
    use FilamentCreateFunctions;
    protected static string $resource = PrivilegeResource::class;
    protected static bool $canCreateAnother = false;
}
