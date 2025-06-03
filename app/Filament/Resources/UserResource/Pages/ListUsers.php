<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use App\Traits\FilamentListFunctions;

class ListUsers extends ListRecords
{
    protected string $routename = 'users';
    use FilamentListFunctions;
    protected static string $resource = UserResource::class;
}
