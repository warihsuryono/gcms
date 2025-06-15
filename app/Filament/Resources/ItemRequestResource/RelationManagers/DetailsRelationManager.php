<?php

namespace App\Filament\Resources\ItemRequestResource\RelationManagers;

use Filament\Forms;
use App\Models\Item;
use App\Models\ItemRequestDetail;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Livewire\Component;
use App\Tables\Columns\SelectCheckbox;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_request_type_id')->relationship('item_request_type', 'name')->searchable()->preload(),
                Select::make('item_id')->options(Item::all()->pluck('name', 'id'))->relationship('item', 'name')->searchable()->preload()
                    ->createOptionForm([
                        Select::make('item_specification_id')->relationship('item_specification', 'name')->searchable()->preload(),
                        Select::make('item_category_id')->relationship('item_category', 'name')->searchable()->preload(),
                        Select::make('item_type_id')->relationship('item_type', 'name')->searchable()->preload(),
                        Select::make('item_brand_id')->relationship('item_brand', 'name')->searchable()->preload(),
                        TextInput::make('name')->maxLength(255),
                        Select::make('unit_id')->relationship('unit', 'name')->searchable()->preload(),
                        TextInput::make('description')->maxLength(255),
                        TextInput::make('minimum_stock')->numeric(),
                        TextInput::make('maximum_stock')->numeric(),
                        TextInput::make('lifetime')->numeric()->default(0),
                    ]),
                TextInput::make('qty')->stripCharacters(',')->numeric(),
                Select::make('unit_id')->options(Unit::all()->pluck('name', 'id'))->relationship('unit', 'name')->searchable()->preload()
                    ->createOptionForm([TextInput::make('name')->maxLength(50)]),
                TextInput::make('notes')->required()->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_id')
            ->columns([
                Tables\Columns\TextColumn::make('item_request_type.name'),
                Tables\Columns\TextColumn::make('item.name'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('notes'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (ItemRequestDetail $detail, Component $livewire) {
                        $detail->update(['seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1]);
                        $livewire->dispatch('refreshItemRequest');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemRequest');
                }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemRequest');
                }),
            ], ActionsPosition::BeforeColumns)
            ->paginated(false);
    }
}
