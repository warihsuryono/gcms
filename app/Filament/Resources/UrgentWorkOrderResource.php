<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Field;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Forms\Components\Map;
use App\Models\UrgentWorkOrder;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Request;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UrgentWorkOrderResource\Pages;
use App\Filament\Resources\UrgentWorkOrderResource\RelationManagers;

class UrgentWorkOrderResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = UrgentWorkOrder::class;
    protected static ?string $routename = 'urgent-work-orders';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')->visibleOn(['edit', 'view'])->disabled(),
                Map::make('map')->columnSpanFull(),
                Forms\Components\DateTimePicker::make('work_at')->required()->default(now()),
                Forms\Components\Select::make('division_id')->relationship('division', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('field_id')->label('Fields')->options(Field::all()->pluck('name', 'id'))->searchable()->preload(),
                RichEditor::make('works')->columnSpanFull()->required()->toolbarButtons(['undo', 'redo'])->helperText('Describe the work to be done.'),
                Forms\Components\Select::make('work_order_status_id')->relationship('work_order_status', 'name', fn($query) => $query->orderBy('id'))->default('1')->required()->reactive(),
                Section::make('Photos')->schema([
                    FileUpload::make('photo_1')->directory('photos')->image()->label('Photo 1')->disabled()->visible(fn($record) => $record->photo_1),
                    FileUpload::make('photo_2')->directory('photos')->image()->label('Photo 2')->disabled()->visible(fn($record) => $record->photo_2),
                    FileUpload::make('photo_3')->directory('photos')->image()->label('Photo 3')->disabled()->visible(fn($record) => $record->photo_3),
                    FileUpload::make('photo_4')->directory('photos')->image()->label('Photo 4')->disabled()->visible(fn($record) => $record->photo_4),
                    FileUpload::make('photo_5')->directory('photos')->image()->label('Photo 5')->disabled()->visible(fn($record) => $record->photo_5),
                ])->columns(2)->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('work_at'),
                Tables\Columns\TextColumn::make('division.name'),
                Tables\Columns\TextColumn::make('field.name'),
                Tables\Columns\TextColumn::make('lat')->label('Latitude')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lon')->label('Longitude')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('work_order_status.name')->label('Status'),
                ImageColumn::make('photo_1'),
                ImageColumn::make('photo_2')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('photo_3')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('photo_4')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('photo_5')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pic'),
                Tables\Columns\TextColumn::make('createdBy.name')->label('Created By'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At'),
                Tables\Columns\TextColumn::make('acceptedBy.name')->label('Accepted By'),
                Tables\Columns\TextColumn::make('accepted_at')->label('Accepted At'),
            ])
            ->filters([
                Filter::make('work_at')
                    ->form([DatePicker::make('work_at_from')->default(now()->firstOfMonth()), DatePicker::make('work_at_until')->default(now()->lastOfMonth())])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['work_at_from'], fn(Builder $query, $date): Builder => $query->whereDate('work_at', '>=', $date))
                            ->when($data['work_at_until'], fn(Builder $query, $date): Builder => $query->whereDate('work_at', '<=', $date));
                    })->columns(2),
                SelectFilter::make('work_order_status_id')->label('Status')->relationship('work_order_status', 'name')
            ])
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns)
            ->modifyQueryUsing(fn(Builder $query) => $query->orderBy('id', 'DESC'))
            ->paginated([
                25,
                50,
                100,
                'all',
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
            'index' => Pages\ListUrgentWorkOrders::route('/'),
            'create' => Pages\CreateUrgentWorkOrder::route('/create'),
            'new' => Pages\NewUrgentWorkOrder::route('/new'),
            'edit' => Pages\EditUrgentWorkOrder::route('/{record}/edit'),
            'view' => Pages\ViewUrgentWorkOrder::route('/{record}'),
        ];
    }
}
