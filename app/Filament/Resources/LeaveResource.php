<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use App\Traits\FilamentListActions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeaveResource extends Resource
{
    use FilamentListActions;

    protected static ?string $routename = "leaves";

    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $startAtDefault = in_array(now()->dayOfWeek(), [0, 6]) ? now()->addDays(2)->format("Y-m-d 08:00") : now()->addWeekday(1)->format("Y-m-d 08:00");
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('start_at')->default($startAtDefault)->required(),
                Forms\Components\DateTimePicker::make('end_at')->default(Carbon::parse($startAtDefault)->addWeekday(3)->format("Y-m-d 17:00"))->required(),
                Forms\Components\Select::make('leave_type_id')
                    ->required()
                    ->relationship('LeaveType', 'name')
                    ->searchable()
                    ->preload() ,
                Forms\Components\FileUpload::make('document')
                    ->directory('leave_documents')
                    ->maxSize(1024*5)
                    ->acceptedFileTypes(['application/pdf', 'image/*']),
                Forms\Components\Textarea::make('reason')
                    ->placeholder('Example: Sick leave')
                    ->columnSpanFull(),
               
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('user.name')->label("Employee Name"),
            TextEntry::make('LeaveType.name'),
            TextEntry::make('start_at')->date('l , j F Y H:i'),
            TextEntry::make('end_at')->date('l, j F Y H:i'),
            TextEntry::make('day_leave')->label('Total Day Leave'),
            TextEntry::make('document')->label('Document')
                ->url(fn (Leave $record): string => $record->document ? asset("storage/".$record->document) : "#")
                ->openUrlInNewTab(fn (Leave $record): bool => $record->document ? true : false)
                ->formatStateUsing(fn ($record) => $record->document ? "Download" : "No Document"),
            TextEntry::make('is_approved')->label('Status Approve')->formatStateUsing(fn ($record) => match ($record->is_approved) {
                1 => 'Approved',
                0 => 'Pending',
            }),
            TextEntry::make('is_acknowlegde')->label('Status Acknowledge')->formatStateUsing(fn ($record) => match ($record->is_acknowlegde) {
                1 => 'Acknowledged',
                0 => 'Pending',
            }),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Employee Name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('LeaveType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_leave')
                    ->label('Total Day Leave')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_approved')
                    ->formatStateUsing(fn ($record) => match ($record->is_approved) {
                        1 => 'Approved',
                        0 => 'Pending',
                        2 => 'Rejected',
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        1 => 'success',
                        0 => 'warning',
                        2 => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_acknowledge')
                    ->formatStateUsing(fn ($record) => match ($record->is_acknowledge) {
                        1 => 'Approved',
                        0 => 'Pending',
                        2 => 'Rejected',
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        1 => 'success',
                        0 => 'warning',
                        2 => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('l, j F Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->since()
                    ->sortable(),
                
            ])
            ->filters([
                //
            ])
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns)
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !is_officer(Auth::user()->id, 'leave-%')
                    && (Auth::user()->employee && Auth::user()->employee->leader_user_id > 0) //not board of directors
                ) { //show only creator
                    $query->where('created_by', Auth::user()->id);
                    return $query;
                }
            })
            ->defaultSort('created_at', 'desc');

    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
            'view' => Pages\ViewLeave::route('/{record}/view'),
        ];
    }
}
