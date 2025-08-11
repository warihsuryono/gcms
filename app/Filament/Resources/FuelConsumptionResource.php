<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FuelConsumption;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\FuelConsumptionExporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FuelConsumptionResource\Pages;
use App\Filament\Resources\FuelConsumptionResource\RelationManagers;

class FuelConsumptionResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = FuelConsumption::class;
    protected static ?string $routename = 'fuel-consumptions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('consumption_at'),
                Forms\Components\Select::make('item_type_id')->relationship('item_type', 'name', fn(Builder $query) => $query->where('id', '<', 3))->required()->default(1)->label('Fuel Type'),
                Forms\Components\Select::make('fuelpowered_equipment_id')->relationship('fuelpowered_equipment', 'name')->label('Fuel Powered Equipment')->searchable()->preload()
                    ->createOptionForm([
                        Forms\Components\Select::make('item_type_id')->relationship('item_type', 'name', fn(Builder $query) => $query->where('id', '<', 3))->required()->default(1)->label('Fuel Type'),
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('quantity')->numeric()->default(0)->suffix('liters'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consumption_at')->date()->sortable(),
                Tables\Columns\TextColumn::make('item_type.name')->label('Fuel Type')->label('Type'),
                Tables\Columns\TextColumn::make('fuelpowered_equipment.name')->label('Fuel Powered Equipment')->label('Equipment'),
                Tables\Columns\TextColumn::make('quantity')->numeric()->sortable()->label('Quantity (Liters)')->alignRight(),
            ])
            ->filters([
                Filter::make('consumption_at')
                    ->form([DatePicker::make('created_from')->label('Consumption From'), DatePicker::make('created_until')->label('Consumption From')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date): Builder => $query->whereDate('consumption_at', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date): Builder => $query->whereDate('consumption_at', '<=', $date));
                    }),
                SelectFilter::make('item_type_id')->label('Fuel Type')->relationship('item_type', 'name', fn(Builder $query) => $query->where('id', '<', 3))->multiple()->preload()
            ])
            ->paginated([
                25,
                50,
                100,
                'all',
            ])
            ->headerActions([
                ExportAction::make('fuel_consumptions_export')
                    ->exporter(FuelConsumptionExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color("success")
                    ->filename('fuel-consumptions-' . time())
                    ->columnMapping(false)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFuelConsumptions::route('/'),
            'create' => Pages\CreateFuelConsumption::route('/create'),
            'report' => Pages\ReportFuelConsumptions::route('/report'),
            'chart' => Pages\ChartFuelConsumptions::route('/chart'),
            'edit' => Pages\EditFuelConsumption::route('/{record}/edit'),
        ];
    }
}
