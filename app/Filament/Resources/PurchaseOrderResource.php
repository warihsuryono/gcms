<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PurchaseOrder;
use App\Models\FollowupOfficer;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers;

class PurchaseOrderResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = PurchaseOrder::class;
    protected static ?string $routename = 'purchase-orders';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        $subtotal = $infolist->record->subtotal;

        if ($infolist->record->discount_is_percentage)
            $discount = $subtotal * $infolist->record->discount / 100;
        else
            $discount = $infolist->record->discount;

        $after_discount = $subtotal - $discount;
        $tax = $after_discount * $infolist->record->tax / 100;
        $total = $after_discount + $tax;

        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('doc_no')->label('Document No'),
                Infolists\Components\TextEntry::make('doc_at')->label('Document Date')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('supplier.name'),
                Infolists\Components\TextEntry::make('delivery_at')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('payment_type.name'),
                Infolists\Components\TextEntry::make('useBy.name'),
                Infolists\Components\TextEntry::make('use_at')->label('Use Date')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('shipment_pic'),
                Infolists\Components\TextEntry::make('shipment_address'),
                Infolists\Components\TextEntry::make('notes'),
                Infolists\Components\TextEntry::make('currency.name'),
                Infolists\Components\TextEntry::make('Sub Total')->state(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($subtotal, 2)),
                Infolists\Components\TextEntry::make('Discount')->state(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($discount, 2)),
                Infolists\Components\TextEntry::make('After Discount')->state(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($after_discount, 2)),
                Infolists\Components\TextEntry::make('Tax')->state(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($tax, 2)),
                Infolists\Components\TextEntry::make('Total')->state(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($total, 2)),
            ]);
    }

    public static function form(Form $form): Form
    {
        if ($form->getRecord()) {
            $subtotal = 0;
            foreach ($form->getRecord()->details as $purchaseOrderDetail)
                $subtotal += ($purchaseOrderDetail->price * $purchaseOrderDetail->qty);

            if ($form->getRecord()->discount_is_percentage)
                $discount = $subtotal * $form->getRecord()->discount / 100;
            else
                $discount = $form->getRecord()->discount;

            $after_discount = $subtotal - $discount;
            $tax = $after_discount * $form->getRecord()->tax / 100;
            $grandtotal = $after_discount + $tax;
            $form->getRecord()->update(['subtotal' => $subtotal, 'grandtotal' => $grandtotal]);
        }
        return $form
            ->schema([
                Forms\Components\TextInput::make('doc_no')->readOnly()->visibleOn('edit'),
                Forms\Components\DatePicker::make('doc_at'),
                Forms\Components\Select::make('supplier_id')->relationship('supplier', 'name')->searchable()->preload(),
                Forms\Components\DatePicker::make('delivery_at'),
                Forms\Components\Select::make('payment_type_id')->relationship('payment_type', 'name')->searchable()->preload(),
                Forms\Components\Select::make('item_request_id')->relationship('item_request', 'item_request_no')->searchable()->preload(),
                Forms\Components\Select::make('use_by')->relationship('useBy', 'name')->required()->searchable()->preload(),
                Forms\Components\DatePicker::make('use_at'),
                Forms\Components\TextInput::make('shipment_pic')->maxLength(255)->required(),
                Forms\Components\Textarea::make('shipment_address')->columnSpanFull(),
                Forms\Components\TextInput::make('notes')->maxLength(255)->required(),
                Forms\Components\Select::make('currency_id')->relationship('currency', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('discount_is_percentage')->options(['1' => 'Yes', '0' => 'No'])->required()->default(1),
                Forms\Components\TextInput::make('discount')->numeric()->default(0),
                Forms\Components\TextInput::make('tax')->numeric()->default(10)->suffix(' %'),
                Placeholder::make('subtotal')->label('Sub Total')->visibleOn('edit')
                    ->content(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($record->subtotal, 2)),
                Placeholder::make('grandtotal')->label('Grand Total')->visibleOn('edit')
                    ->content(fn(PurchaseOrder $record) => $record->currency->symbol . '. ' . number_format($record->grandtotal, 2)),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doc_no')->searchable(),
                Tables\Columns\TextColumn::make('doc_at')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('supplier.name'),
                Tables\Columns\TextColumn::make('delivery_at')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('payment_type.name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('purchase_request.doc_no'),
                Tables\Columns\TextColumn::make('useBy.name'),
                Tables\Columns\TextColumn::make('use_at')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('shipment_pic'),
                Tables\Columns\TextColumn::make('currency.name'),
                Tables\Columns\TextColumn::make('tax')->numeric()->suffix(' %'),
                Tables\Columns\TextColumn::make('grandtotal')->state(fn(PurchaseOrder $record) => $record->currency->symbol . ". " . number_format($record->grandtotal, 2)),
                Tables\Columns\TextColumn::make('notes')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('approvedBy.name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('approved_at')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sent_at')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('closed_at')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: true),
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
                TernaryFilter::make('is_sent'),
                TernaryFilter::make('is_closed')->default(fn() => Request::get('is_open') ? 0 : ''),
                TernaryFilter::make('is_approved'),
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
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'view' => Pages\ViewPurchaseOrder::route('/{record}'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
