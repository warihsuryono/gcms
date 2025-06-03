<?php

namespace App\Filament\Resources;

use App\Models\Icon;
use App\Models\menu;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use App\Tables\Columns\ReorderButton;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\MenuResource\Pages;

class MenuResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = menu::class;
    protected static ?string $routename = 'menus';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('url'),
                Select::make('icon')
                    ->options(Icon::all()->pluck('name', 'name'))
                    ->searchable(),
                TextInput::make('route')->nullable(),
                TextInput::make('middleware'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                ReorderButton::make('seqno')->label("Seq No")->sortable(),
                TextColumn::make('url')->searchable(),
                TextColumn::make('icon'),
                TextColumn::make('route'),
                TextColumn::make('middleware')
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('parent_id', '=', '0');
            })
            ->filters([
                //
            ])
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
