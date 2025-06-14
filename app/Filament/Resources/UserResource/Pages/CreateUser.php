<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentCreateFunctions;

class CreateUser extends CreateRecord
{
    protected $routename = 'users';
    use FilamentCreateFunctions;
    protected static string $resource = UserResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.users.index', $this->record->id);
    }
}
