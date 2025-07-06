<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use App\Models\FuelConsumption;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\FuelConsumptionExporter;
use Filament\Tables\Concerns\InteractsWithTable;


class ReportFuelConsumptions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $model = FuelConsumption::class;

    protected static string $resource = FuelConsumptionResource::class;

    protected static string $view = 'filament.resources.fuel-consumption-resource.pages.report-fuel-consumptions';

    protected static ?string $title = 'Fuel Consumptions Report';

    public static function table(Table $table): Table
    {
        return $table
            ->query(FuelConsumption::query())
            ->columns([
                TextColumn::make('consumption_at')->date()->sortable(),
                TextColumn::make('item_type.name')->label('Fuel Type'),
                TextColumn::make('fuelpowered_equipment.name')->label('Fuel Powered Equipment'),
                TextColumn::make('quantity')->numeric()->sortable()->label('Quantity (Liters)')->alignRight(),
            ])
            ->filters([
                Filter::make('consumption_at')
                    ->form([DatePicker::make('created_from')->label('Consumption From'), DatePicker::make('created_until')->label('Consumption Until')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date): Builder => $query->whereDate('consumption_at', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date): Builder => $query->whereDate('consumption_at', '<=', $date));
                    }),
                SelectFilter::make('item_type_id')->label('Fuel Type')->relationship('item_type', 'name', fn(Builder $query) => $query->where('id', '<', 3))->multiple()->preload()
            ])
            ->paginated(false)
            ->headerActions([
                Action::make('chart_fuel_consumptions')
                    ->label('Chart Fuel Consumptions')
                    ->action(fn() => redirect()->route('filament.' . env('PANEL_PATH') . '.resources.fuel-consumptions.chart'))
                    ->icon('heroicon-o-presentation-chart-bar')
                    ->color('primary'),
                ExportAction::make()
                    ->exporter(FuelConsumptionExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color("success")
                    ->filename('fuel-consumptions-' . time())
                    ->columnMapping(false),
            ]);
    }
}
