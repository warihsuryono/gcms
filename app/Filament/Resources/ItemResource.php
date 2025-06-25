<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\WarehouseDetail;

class ItemResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Item::class;
    protected static ?string $routename = 'items';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')->maxLength(255)->unique(),
                Forms\Components\Select::make('item_specification_id')->relationship('item_specification', 'name')->searchable()->preload()->label('Specification'),
                Forms\Components\Select::make('item_category_id')->relationship('item_category', 'name')->searchable()->preload()->label('Category'),
                Forms\Components\Select::make('item_type_id')->relationship('item_type', 'name')->searchable()->preload()->label('Type'),
                Forms\Components\Select::make('item_brand_id')->relationship('item_brand', 'name')->searchable()->preload()->label('Brand'),
                Forms\Components\TextInput::make('name')->maxLength(255)->unique(),
                Forms\Components\Select::make('unit_id')->relationship('unit', 'name')->searchable()->preload(),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
                Forms\Components\TextInput::make('minimum_stock')->numeric()->default(0),
                Forms\Components\TextInput::make('maximum_stock')->numeric()->default(0),
                Forms\Components\TextInput::make('lifetime')->numeric()->default(0),
                Forms\Components\Select::make('warehouse_detail_ids')->label('Storage Locations')->options(WarehouseDetail::all()->pluck('code', 'id'))->searchable()->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('item_specification.name')->label('Specification'),
                Tables\Columns\TextColumn::make('item_category.name')->label('Category'),
                Tables\Columns\TextColumn::make('item_type.name')->label('Type'),
                Tables\Columns\TextColumn::make('item_brand.name')->label('Brand'),
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('minimum_stock')->numeric(),
                Tables\Columns\TextColumn::make('maximum_stock')->numeric(),
                Tables\Columns\TextColumn::make('lifetime')->numeric(),
                Tables\Columns\TextColumn::make('warehouse_detail_ids')->label('Storage Locations')->formatStateUsing(function ($state, $record) {
                    $warehouse_detail_ids = json_decode($record->warehouse_detail_ids);
                    $warehouse_details = "";
                    foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                        $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                    }
                    return substr($warehouse_details, 0, -2);
                })->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('item_specification')->relationship('item_specification', 'name')->searchable()->preload()->label('Specification'),
                SelectFilter::make('item_category')->relationship('item_category', 'name')->searchable()->preload()->label('Category'),
                SelectFilter::make('item_type')->relationship('item_type', 'name')->searchable()->preload()->label('Type'),
                SelectFilter::make('item_brand')->relationship('item_brand', 'name')->searchable()->preload()->label('Brand'),
            ])
            ->filtersFormColumns(2)
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns);
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
            'view' => Pages\ViewItem::route('/{record}'),
        ];
    }
}
