<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Traits\FilamentListActions;

class EmployeeResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Employee::class;
    protected static ?string $routename = 'employees';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name'),
                Infolists\Components\TextEntry::make('leader.name'),
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('nik'),
                Infolists\Components\TextEntry::make('nip'),
                Infolists\Components\TextEntry::make('contract_no'),
                Infolists\Components\TextEntry::make('bpjs_kesehatan'),
                Infolists\Components\TextEntry::make('bpjs_ketenagakerjaan'),
                Infolists\Components\TextEntry::make('kk'),
                Infolists\Components\TextEntry::make('npwp'),
                Infolists\Components\TextEntry::make('phone'),
                Infolists\Components\TextEntry::make('join_at')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('gender'),
                Infolists\Components\TextEntry::make('birth_place'),
                Infolists\Components\TextEntry::make('birth_at')->date('d-m-Y'),
                Infolists\Components\TextEntry::make('address'),
                Infolists\Components\TextEntry::make('domicile_address'),
                Infolists\Components\TextEntry::make('employee_status.name'),
                Infolists\Components\TextEntry::make('marriage_status.name'),
                Infolists\Components\TextEntry::make('degree.name'),
                Infolists\Components\TextEntry::make('major'),
                Infolists\Components\TextEntry::make('level_title'),
                Infolists\Components\ImageEntry::make('user.signature')->label('Signature'),
                Infolists\Components\ImageEntry::make('user.photo')->label('Photo'),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('leader_user_id')->relationship('leader', 'name')->searchable()->preload(),
                Forms\Components\TextInput::make('name')->maxLength(255)->required(),
                Forms\Components\TextInput::make('nik')->maxLength(50),
                Forms\Components\TextInput::make('nip')->maxLength(20),
                Forms\Components\TextInput::make('contract_no')->maxLength(30),
                Forms\Components\TextInput::make('bpjs_kesehatan')->maxLength(30),
                Forms\Components\TextInput::make('bpjs_ketenagakerjaan')->maxLength(30),
                Forms\Components\TextInput::make('kk')->maxLength(30),
                Forms\Components\TextInput::make('npwp')->maxLength(30),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(30)->required(),
                Forms\Components\DatePicker::make('join_at'),
                Forms\Components\Select::make('gender')->options(['male' => 'Male', 'female' => 'Female'])->required(),
                Forms\Components\TextInput::make('birth_place')->maxLength(30),
                Forms\Components\DatePicker::make('birth_at'),
                Forms\Components\TextInput::make('address')->maxLength(255),
                Forms\Components\TextInput::make('domicile_address')->maxLength(255),
                Forms\Components\Select::make('employee_status_id')->relationship('employee_status', 'name')->required(),
                Forms\Components\Select::make('marriage_status_id')->relationship('marriage_status', 'name')->required(),
                Forms\Components\Select::make('degree_id')->relationship('degree', 'name')->required(),
                Forms\Components\TextInput::make('major')->maxLength(100),
                Forms\Components\TextInput::make('level_title')->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('user.photo'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('leader.name'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('nik')->searchable(),
                Tables\Columns\TextColumn::make('nip')->searchable(),
                Tables\Columns\TextColumn::make('contract_no')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('bpjs_kesehatan')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('bpjs_ketenagakerjaan')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('kk')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('npwp')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('join_at')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('birth_place')->searchable(),
                Tables\Columns\TextColumn::make('birth_at')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('address')->toggleable(isToggledHiddenByDefault: true)->searchable()->wrap(),
                Tables\Columns\TextColumn::make('domicile_address')->toggleable(isToggledHiddenByDefault: true)->searchable()->wrap(),
                Tables\Columns\TextColumn::make('employee_status.name'),
                Tables\Columns\TextColumn::make('marriage_status.name'),
                Tables\Columns\TextColumn::make('degree.name'),
                Tables\Columns\TextColumn::make('major')->searchable(),
                Tables\Columns\TextColumn::make('level_title')->searchable()
            ])
            ->filters([
                SelectFilter::make('leader')->multiple()->relationship('leader', 'name')->searchable()->preload(),
                SelectFilter::make('gender')->options(['male' => 'Male', 'female' => 'Female']),
                SelectFilter::make('employee_status')->multiple()->relationship('employee_status', 'name')->searchable()->preload(),
                SelectFilter::make('marriage_status')->multiple()->relationship('marriage_status', 'name')->searchable()->preload(),
                SelectFilter::make('degree')->multiple()->relationship('degree', 'name')->searchable()->preload(),
            ])
            ->filtersFormColumns(4)
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
