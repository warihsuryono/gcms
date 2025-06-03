<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Traits\FilamentListFunctions;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaves extends ListRecords
{
    protected $routename = "leaves";
    use FilamentListFunctions;
    protected static string $resource = LeaveResource::class;
}
