<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use App\Models\Privilege;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\EditUser;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use App\Filament\Resources\UserResource\Pages\CreateUser;

class UserResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = User::class;
    protected static ?string $routename = 'users';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->required()->unique(ignoreRecord: true)->email(),
                    Select::make('privilege_id')->options(Privilege::all()->pluck('name', 'id'))
                        ->relationship('privilege', 'name')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required(),

                        ]),
                    TextInput::make('password')
                        ->required()
                        ->password()
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->visible(fn($livewire) => $livewire instanceof CreateUser)
                        ->rule(Password::default()),
                    TextInput::make('msisdn')->label('Mobile Phone')->prefix('+62')->tel()->required(),
                    FileUpload::make('signature')
                        ->directory('signatures')
                        ->image()->imageEditor(),
                    FileUpload::make('photo')
                        ->directory('photos')
                        ->image()->imageEditor(),
                ]),
                Section::make('User New Password')->schema([
                    TextInput::make('new_password')->nullable()->password()->rule(Password::default()),
                    TextInput::make('new_password_confirmation')->password()->same('new_password')->requiredWith('new_password'),
                ])->visible(fn($livewire) => $livewire instanceof EditUser),
            ]);
    }

    public static function table(Table $table): Table
    {
        $actions = self::actions(self::$routename);
        if (Auth::user()->id == 1) array_push($actions, Impersonate::make());
        return $table
            ->columns([
                ImageColumn::make('photo'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('privilege.name')->searchable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('id', '>', '1');
            })
            ->filters([
                SelectFilter::make('privilege_id')->relationship('privilege', 'name')
            ])
            ->actions($actions, ActionsPosition::BeforeColumns);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
