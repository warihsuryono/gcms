<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemRequestResource\Pages;
use App\Filament\Resources\ItemRequestResource\RelationManagers;
use App\Models\ItemRequest;
use App\Traits\FilamentListActions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\FollowupOfficer;
use App\Models\User;
use App\Models\WorkOrder;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Request;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;


class ItemRequestResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = ItemRequest::class;
    protected static ?string $routename = 'item-requests';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item_request_no')->readOnly()->visibleOn('edit'),
                Forms\Components\DatePicker::make('item_request_at')->default(now())->required()->label('Request At'),
                Forms\Components\Select::make('user_id')->options(User::all()->pluck('name', 'id'))->relationship('user', 'name')->default(Auth::user()->id)->disabled(),
                Forms\Components\RichEditor::make('description')->columnSpanFull()->default(fn() => Request::get('work_order_id') > 0 ? WorkOrder::find(Request::get('work_order_id'))->works : '')
                    ->toolbarButtons(['undo', 'redo']),
                Forms\Components\Hidden::make('work_order_id')->default(fn() => Request::get('work_order_id') > 0 ? Request::get('work_order_id') : 0),

            ]);
    }

    public static function table(Table $table): Table
    {
        $actions = self::actions(self::$routename);
        array_push(
            $actions,
            Action::make('View Work Order')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('primary')
                ->visible(fn($record) => $record->work_order_id > 0)
                ->action(function ($record) {
                    redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.view', $record->work_order_id);
                })->iconButton()
        );

        $table->actions($actions, ActionsPosition::BeforeColumns);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_request_no')->searchable()->label('Request No'),
                Tables\Columns\TextColumn::make('item_request_at'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('issuedBy.name')->label('Issued By'),
                Tables\Columns\TextColumn::make('receivedBy.name')->label('Received By'),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([DatePicker::make('created_from'), DatePicker::make('created_until')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
                SelectFilter::make('user')->relationship('user', 'name')->searchable()->preload(),
                SelectFilter::make('created_by')->relationship('createdBy', 'name')->searchable()->preload(),
                TernaryFilter::make('is_issued')->default(fn() => Request::get('is_open') ? 0 : ''),
                TernaryFilter::make('is_received'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::whereLike('action', 'item-request-%')->where('user_id', Auth::user()->id)->first() //not followup officer
                ) { //show only creator
                    $query->where('created_by', Auth::user()->id);
                    return $query;
                }
            })
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
            'index' => Pages\ListItemRequests::route('/'),
            'create' => Pages\CreateItemRequest::route('/create'),
            'edit' => Pages\EditItemRequest::route('/{record}/edit'),
            'view' => Pages\ViewItemRequest::route('/{record}')
        ];
    }
}
