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
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;


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
                Forms\Components\TextInput::make('user_id')->default(Auth::user()->name)->disabled()->label('Requested By'),
                // Forms\Components\Select::make('work_order_id')->relationship('work_order', 'id'),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_request_no')->searchable()->label('Request No'),
                Tables\Columns\TextColumn::make('item_request_at'),
                Tables\Columns\TextColumn::make('user.name'),
                // Tables\Columns\TextColumn::make('work_order.id'),
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
                TernaryFilter::make('is_issued'),
                TernaryFilter::make('is_received'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::whereLike('action', 'item-request-%')->where('user_id', Auth::user()->id)->first() //not followup officer
                    && (Auth::user()->employee && Auth::user()->employee->leader_user_id > 0) //not board of directors
                ) { //show only creator
                    $query->where('created_by', Auth::user()->id);
                    return $query;
                }
            })
            ->filtersFormColumns(3)
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns);
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
        ];
    }
}
