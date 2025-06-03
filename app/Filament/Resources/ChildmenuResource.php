<?php

namespace App\Filament\Resources;

use App\Models\menu;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use App\Tables\Columns\ReorderButton;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\ChildmenuResource\Pages;
use App\Traits\FilamentListActions;

class ChildmenuResource extends Resource
{
    use FilamentListActions;
    protected static ?string $label = "Child Menu";
    protected static ?string $model = menu::class;
    protected static ?string $routename = 'menus';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')->label('Parent')->options(Menu::where(['parent_id' => '0'])->get()->pluck('name', 'id')),
                TextInput::make('name'),
                TextInput::make('url'),
                TextInput::make('route')->nullable(),
                TextInput::make('middleware'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parentMenu.name')->label('Parent')->searchable(),
                TextColumn::make('name')->searchable(),
                ReorderButton::make('seqno')->label("Seq No")->sortable(),
                TextColumn::make('url')->searchable(),
                TextColumn::make('route'),
                TextColumn::make('middleware')
            ])
            ->groups([Group::make('parent_id')
                ->collapsible()])
            ->defaultGroup('parent_id')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('parent_id', '<>', '0');
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
            'index' => Pages\ListChildmenus::route('/'),
            'create' => Pages\CreateChildmenu::route('/create'),
            'edit' => Pages\EditChildmenu::route('/{record}/edit'),
        ];
    }
}
