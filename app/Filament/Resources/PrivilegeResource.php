<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use App\Models\Privilege;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\PrivilegeResource\Pages;

class PrivilegeResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Privilege::class;
    protected static ?string $routename = 'privileges';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('id', '>', '1');
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
            'index' => Pages\ListPrivileges::route('/'),
            'create' => Pages\CreatePrivilege::route('/create'),
            'edit' => Pages\EditPrivilege::route('/{record}/edit'),
        ];
    }
}
