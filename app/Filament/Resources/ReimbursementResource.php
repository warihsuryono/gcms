<?php

namespace App\Filament\Resources;

use App\Models\Bank;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reimbursement;
use App\Tables\Columns\IsPaid;
use App\Models\FollowupOfficer;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\ReimbursementResource\Pages;
use App\Filament\Resources\ReimbursementResource\RelationManagers;

class ReimbursementResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Reimbursement::class;
    protected static ?string $routename = 'reimbursements';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name'),
                Infolists\Components\TextEntry::make('bank.name'),
                Infolists\Components\TextEntry::make('bank_account_name')->label('Bank Atas Nama'),
                Infolists\Components\TextEntry::make('bank_account_no')->label('Nomor Rekening'),
                Infolists\Components\TextEntry::make('total')->numeric()->prefix('Rp. '),
                Infolists\Components\TextEntry::make('notes')->columnSpanFull(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('bank_id')->options(Bank::all()->pluck('name', 'id'))
                    ->relationship('bank', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),
                TextInput::make('bank_account_name')->required()->label('Bank Atas Nama'),
                TextInput::make('bank_account_no')->required()->label('Nomor Rekening'),
                TextInput::make('notes')->required(),
                Placeholder::make('total')
                    ->label('Total')
                    ->content(fn(Reimbursement $record) => 'Rp. ' . number_format($record->total, 2))
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->searchable(),
                TextColumn::make('created_at')->dateTime()->searchable(),
                TextColumn::make('notes')->searchable(),
                TextColumn::make('total')->money('Rp. '),
                IsPaid::make('is_paid')->sortable(),
                TextColumn::make('approvedBy.name')->label('Approved By'),
                TextColumn::make('acknowledgeBy.name')->label('Acknowledge By')
            ])
            ->filters([
                SelectFilter::make('user')->relationship('user', 'name')->searchable()->preload(),
                TernaryFilter::make('is_paid'),
                TernaryFilter::make('is_approved'),
                TernaryFilter::make('is_acknowledge'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::whereLike('action', 'reimbursement-%')->where('user_id', Auth::user()->id)->first() //not followup officer
                    && (Auth::user()->employee && Auth::user()->employee->leader_user_id > 0) //not board of directors
                ) { //show only creator
                    $query->where('user_id', Auth::user()->id)->orWhere('created_by', Auth::user()->id);
                    return $query;
                }
            })
            ->filtersFormColumns(2)
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReimbursements::route('/'),
            'create' => Pages\CreateReimbursement::route('/create'),
            'view' => Pages\ViewReimbursement::route('/{record}'),
            'edit' => Pages\EditReimbursement::route('/{record}/edit'),
        ];
    }
   
}
