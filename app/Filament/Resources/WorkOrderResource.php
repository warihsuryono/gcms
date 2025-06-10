<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\WorkOrder;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\WorkOrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkOrderResource\RelationManagers;
use App\Models\Field;
use Filament\Forms\Components\RichEditor;

class WorkOrderResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = WorkOrder::class;
    protected static ?string $routename = 'work-orders';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('work_start')->required(),
                Forms\Components\DateTimePicker::make('work_end'),
                Forms\Components\Select::make('division_id')->relationship('division', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('field_ids')->label('Fields')->options(Field::all()->pluck('name', 'id'))->searchable()->preload()->multiple(),
                RichEditor::make('works')->columnSpanFull()->required()->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'numberedList', 'blockquote', 'codeBlock', 'undo', 'redo'])
                    ->helperText('Describe the work to be done.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('work_start')->dateTime(),
                Tables\Columns\TextColumn::make('work_end')->dateTime(),
                Tables\Columns\TextColumn::make('division.name'),
                Tables\Columns\TextColumn::make('field_ids')->label('Fields')->formatStateUsing(function ($state, $record) {
                    $field_ids = json_decode($record->field_ids);
                    $fields = "";
                    foreach ($field_ids as $field_id) {
                        $fields .= Field::find($field_id)->name . "<br>";
                    }
                    return $fields;
                })->html(),
                Tables\Columns\TextColumn::make('works')->searchable()->html(),
            ])
            ->filters([
                //
            ])
            ->paginated([
                25,
                50,
                100,
                'all',
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
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
