<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ItemReceipt;
use App\Models\PurchaseOrder;
use App\Models\FollowupOfficer;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemReceiptResource\Pages;
use App\Filament\Resources\ItemReceiptResource\Pages\CreateItemReceipt;
use App\Filament\Resources\ItemReceiptResource\RelationManagers;

class ItemReceiptResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = ItemReceipt::class;
    protected static ?string $routename = 'item-receipts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item_receipt_no')->readOnly()->visibleOn('edit'),
                Forms\Components\DatePicker::make('item_receipt_at')->default(now())->required()->label('Receipt At'),
                Select::make('purchase_order_id')
                    ->searchable()->preload()->required()->live()->label('Purchase Request')
                    ->options(function () {
                        return PurchaseOrder::where('is_closed', 0)->get()->mapWithKeys(function ($purchase_order) {
                            return [$purchase_order->id => "[" . $purchase_order->doc_no . "] -- " . date('d F Y', strtotime($purchase_order->doc_at))];
                        });
                    }),
                // Forms\Components\Select::make('supplier_id')->relationship('supplier', 'name'),
                // Section::make('Shipment Details')->columns(2)
                //     ->schema([
                //         TextInput::make('shipment_company')->maxLength(255),
                //         TextInput::make('shipment_pic')->maxLength(255),
                //         TextInput::make('shipment_phone')->tel()->maxLength(255),
                //         Textarea::make('shipment_address')->columnSpanFull(),
                //         DatePicker::make('shipment_at'),
                //     ]),
                RichEditor::make('description')->columnSpanFull()->toolbarButtons(['numberedList', 'undo', 'redo']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_receipt_no')->searchable()->label('Receipt No'),
                Tables\Columns\TextColumn::make('item_receipt_at'),
                Tables\Columns\TextColumn::make('purchase_order.code')->label('Purchase Request No'),
                // Tables\Columns\TextColumn::make('supplier.name'),
                // Tables\Columns\TextColumn::make('shipment_company'),
                // Tables\Columns\TextColumn::make('shipment_pic'),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By'),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([DatePicker::make('created_from'), DatePicker::make('created_until')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
                SelectFilter::make('created_by')->relationship('createdBy', 'name')->searchable()->preload(),
                TernaryFilter::make('is_approved'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::whereLike('action', 'item-receipt-%')->where('user_id', Auth::user()->id)->first() //not followup officer
                    && !FollowupOfficer::whereLike('action', 'stock-keeper-%')->where('user_id', Auth::user()->id)->first() //not stock keeper
                ) { //show only creator
                    $query->where('created_by', Auth::user()->id);
                    return $query;
                }
            })
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns)
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
            'index' => Pages\ListItemReceipts::route('/'),
            'create' => Pages\CreateItemReceipt::route('/create'),
            'edit' => Pages\EditItemReceipt::route('/{record}/edit'),
            'view' => Pages\ViewItemReceipt::route('/{record}'),
        ];
    }
}
