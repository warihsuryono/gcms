<?php

namespace App\Filament\Resources;

use id;
use Filament\Forms;
use Filament\Tables;
use App\Models\Field;
use Filament\Forms\Form;
use App\Models\WorkOrder;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\WorkOrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkOrderResource\RelationManagers;
use App\Models\WorkOrderStatus;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Request;

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
                Forms\Components\DateTimePicker::make('work_start')->required()->default(now()),
                Forms\Components\DateTimePicker::make('work_end'),
                Forms\Components\Select::make('division_id')->relationship('division', 'name')->searchable()->preload()->required()->default(
                    fn() => Request::get('work_order_id') > 0 ? WorkOrder::find(Request::get('work_order_id'))->division_id : null
                ),
                Forms\Components\Select::make('field_ids')->label('Fields')->options(Field::all()->pluck('name', 'id'))->searchable()->preload()->multiple(),
                RichEditor::make('works')->columnSpanFull()->required()->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'numberedList', 'blockquote', 'codeBlock', 'undo', 'redo'])
                    ->helperText('Describe the work to be done.'),
                Forms\Components\Select::make('work_order_status_id')->relationship('work_order_status', 'name', fn($query) => $query->orderBy('id'))->default('1')->required()->reactive(),
                Forms\Components\Toggle::make('is_next_work_order')->default(0)->label('Proceed with the next work order?')->visible(fn($get) => $get('work_order_status_id') > 2),
                Forms\Components\Hidden::make('prev_work_order_id')->default(fn() => Request::get('work_order_id') > 0 ? Request::get('work_order_id') : 0),
            ]);
    }

    public static function table(Table $table): Table
    {
        $additional_action =
            Action::make('Create Next Work Order')
            ->icon('heroicon-o-document-plus')
            ->color('success')
            ->visible(fn($record) => $record->is_next_work_order == 1)
            ->action(function ($record) {
                redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.create', ['work_order_id' => $record->id]);
            })->iconButton();
        $actions = self::actions(self::$routename);
        array_push($actions, $additional_action);
        $table->actions($actions, ActionsPosition::BeforeColumns);
        return $table
            ->columns([
                SelectColumn::make('work_order_status_id')->options(WorkOrderStatus::all()->pluck('name', 'id'))->label('Status'),
                Tables\Columns\TextColumn::make('work_start')->dateTime(),
                Tables\Columns\TextColumn::make('work_end')->dateTime(),
                Tables\Columns\TextColumn::make('division.name'),
                Tables\Columns\TextColumn::make('field_ids')->label('Fields')->formatStateUsing(function ($record) {
                    $field_ids = json_decode($record->field_ids);
                    $fields = "";
                    foreach ($field_ids as $field_id) {
                        $fields .= Field::find($field_id)->name . "<br>";
                    }
                    return $fields;
                })->html()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('works')->searchable()->html(),
            ])
            ->filters([
                SelectFilter::make('work_order_status_id')->relationship('work_order_status', 'name')
            ])
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
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            // 'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
