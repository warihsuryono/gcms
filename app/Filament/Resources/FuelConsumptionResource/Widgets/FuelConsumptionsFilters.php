<?php

namespace App\Filament\Resources\FuelConsumptionResource\Widgets;

use App\Models\ItemType;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class FuelConsumptionsFilters extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.fuel-consumption-resource.widgets.fuel-consumptions-filters';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        DatePicker::make('consumption_from')->extraInputAttributes(['type' => 'month']),
                        DatePicker::make('consumption_until')->extraInputAttributes(['type' => 'month']),
                        Select::make('item_type_id')->label('Fuel Type')->options(ItemType::where('id', '<', 3)->get()->pluck('name', 'id')),
                        Actions::make([
                            Action::make('Load')
                                ->action(function (Get $get) {
                                    $this->dispatch('updateFilter', consumption_from: $get('consumption_from') ?? '', consumption_until: $get('consumption_until') ?? '', item_type_id: $get('item_type_id') ?? 0);
                                })
                        ]),
                    ])->columns(3),
            ]);
    }
}
