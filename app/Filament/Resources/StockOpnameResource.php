<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\StockOpname;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StockOpnameResource\Pages;
use App\Filament\Resources\StockOpnameResource\Pages\CreateStockOpname;
use App\Filament\Resources\StockOpnameResource\RelationManagers;
use Filament\Forms\Components\Textarea;

class StockOpnameResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = StockOpname::class;
    protected static ?string $routename = 'stock-opnames';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('stock_opname_at')->default(fn($livewire) => $livewire instanceof CreateStockOpname ? now() : null),
                Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name')->default(0),
                Textarea::make('notes')->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock_opname_at')->date(),
                Tables\Columns\TextColumn::make('warehouse.name'),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By'),
                Tables\Columns\TextColumn::make('approved_at')->dateTime(),

            ])
            ->filters([
                Filter::make('stock_opname_at')->label('Stock Opname Date')
                    ->form([DatePicker::make('stock_opname_from'), DatePicker::make('stock_opname_until')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['stock_opname_from'], fn(Builder $query, $date): Builder => $query->whereDate('stock_opname_at', '>=', $date))
                            ->when($data['stock_opname_until'], fn(Builder $query, $date): Builder => $query->whereDate('stock_opname_at', '<=', $date));
                    }),
                SelectFilter::make('warehouse_id')->relationship('warehouse', 'name')->searchable()->preload(),
                SelectFilter::make('created_by')->relationship('createdBy', 'name')->searchable()->preload(),
                TernaryFilter::make('is_approved'),
            ])
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns)
            ->filtersFormColumns(3);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'edit' => Pages\EditStockOpname::route('/{record}/edit'),
            'view' => Pages\ViewStockOpname::route('/{record}'),
        ];
    }
}
