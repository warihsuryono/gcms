<?php

namespace App\Filament\Resources\WorkOrderResource\Widgets;

use App\Models\Division;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class WorkOrdersFilters extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.work-order-resource.widgets.filters-work-orders';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        DatePicker::make('work_start_from'),
                        DatePicker::make('work_start_until'),
                        Select::make('division_id')->options(Division::all()->pluck('name', 'id'))->label('Division'),
                        Actions::make([
                            Action::make('Load')
                                ->action(function (Get $get) {
                                    $this->dispatch('updateFilter', work_start_from: $get('work_start_from') ?? '', work_start_until: $get('work_start_until') ?? '', division_id: $get('division_id') ?? 0);
                                })
                        ]),
                    ])->columns(3),
            ]);
    }
}
