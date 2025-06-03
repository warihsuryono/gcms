<?php

namespace App\Filament\Resources\ProfileResource\Pages;

use Illuminate\Support\Facades\App;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProfileResource;

class CreateProfile extends CreateRecord
{
    protected function beforeFill(): void
    {
        redirect(App::make('url')->to(env('PANEL_PATH') . '/profiles'));
    }
    protected static string $resource = ProfileResource::class;
    protected static bool $canCreateAnother = false;
}
