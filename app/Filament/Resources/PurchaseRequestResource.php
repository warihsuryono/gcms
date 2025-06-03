<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FollowupOfficer;
use App\Models\PurchaseRequest;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\PurchaseRequestResource\Pages;
use App\Filament\Resources\PurchaseRequestResource\RelationManagers;

class PurchaseRequestResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = PurchaseRequest::class;
    protected static ?string $routename = 'purchase-requests';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        $subtotal = $infolist->record->subtotal;
        $tax = $subtotal * $infolist->record->tax / 100;
        $total = $subtotal + $tax;

        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('doc_no')->label('Document No'),
                Infolists\Components\TextEntry::make('doc_at')->label('Document Date')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('useBy.name'),
                Infolists\Components\TextEntry::make('use_at')->label('Use Date')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('description'),
                Infolists\Components\TextEntry::make('currency.name'),
                Infolists\Components\TextEntry::make('Sub Total')->state(fn(PurchaseRequest $record) => $record->currency->symbol . '. ' . number_format($subtotal, 2)),
                Infolists\Components\TextEntry::make('Tax')->state(fn(PurchaseRequest $record) => $record->currency->symbol . '. ' . number_format($tax, 2)),
                Infolists\Components\TextEntry::make('Total')->state(fn(PurchaseRequest $record) => $record->currency->symbol . '. ' . number_format($total, 2)),
            ]);
    }

    public static function form(Form $form): Form
    {
        if ($form->getRecord()) {
            $subtotal = 0;
            foreach ($form->getRecord()->details as $purchaseRequestDetail)
                $subtotal += ($purchaseRequestDetail->price * $purchaseRequestDetail->qty);

            $tax = $subtotal * $form->getRecord()->tax / 100;
            $grandtotal = $subtotal + $tax;
            $form->getRecord()->update(['subtotal' => $subtotal, 'grandtotal' => $grandtotal]);
        }
        return $form
            ->schema([
                Forms\Components\TextInput::make('doc_no')->readOnly()->visibleOn('edit'),
                Forms\Components\DatePicker::make('doc_at'),
                Forms\Components\Select::make('use_by')->relationship('useBy', 'name')->required()->searchable()->preload(),
                Forms\Components\DatePicker::make('use_at'),
                Forms\Components\TextInput::make('description')->maxLength(255),
                Forms\Components\Select::make('currency_id')->relationship('currency', 'name')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('tax')->default('10')->suffix(' %')->numeric(),
                Placeholder::make('total')
                    ->label('Total')
                    ->content(fn(PurchaseRequest $record) => $record->currency->symbol . '. ' . number_format($record->price, 2))
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doc_no')->searchable(),
                Tables\Columns\TextColumn::make('doc_at')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('useBy.name'),
                Tables\Columns\TextColumn::make('use_at')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('currency.name'),
                Tables\Columns\TextColumn::make('tax')->numeric()->suffix(' %'),
                Tables\Columns\TextColumn::make('grandtotal')->state(fn(PurchaseRequest $record) => $record->currency->symbol . ". " . number_format($record->grandtotal, 2)),
                Tables\Columns\TextColumn::make('createdBy.name')->label('Created By'),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By'),
                Tables\Columns\TextColumn::make('acknowledgeBy.name')->label('Acknowledge By')
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([DatePicker::make('created_from'), DatePicker::make('created_until')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
                SelectFilter::make('use_by')->relationship('useBy', 'name')->searchable()->preload(),
                SelectFilter::make('created_by')->relationship('createdBy', 'name')->searchable()->preload(),
                TernaryFilter::make('is_approved'),
                TernaryFilter::make('is_acknowledge'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::whereLike('action', 'purchase-request-%')->where('user_id', Auth::user()->id)->first() //not followup officer
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
            'index' => Pages\ListPurchaseRequests::route('/'),
            'create' => Pages\CreatePurchaseRequest::route('/create'),
            'view' => Pages\ViewPurchaseRequest::route('/{record}'),
            'edit' => Pages\EditPurchaseRequest::route('/{record}/edit'),
        ];
    }
}
