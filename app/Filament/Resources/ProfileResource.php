<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use App\Models\Privilege;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rules\Password;
use App\Filament\Resources\ProfileResource\Pages;
use Illuminate\Support\Facades\Auth;

class ProfileResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $routename = 'profiles';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->required()->disabled(true),
                    Select::make('privilege_id')->options(Privilege::all()->pluck('name', 'id'))
                        ->relationship('privilege', 'name')
                        ->disabled(true),
                    TextInput::make('msisdn')->label('Mobile Phone')->tel()->required(),
                    // FileUpload::make('signature')->directory('signatures')->image()->imageEditor(),
                    // FileUpload::make('photo')->directory('photos')->image()->imageEditor(),
                ]),
                Section::make('User New Password')->schema([
                    TextInput::make('new_password')->nullable()->password()->rule(Password::default()),
                    TextInput::make('new_password_confirmation')->password()->same('new_password')->requiredWith('new_password'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        redirect(env('PANEL_PATH') . '/profiles/' . Auth::user()->id . '/edit');
        return $table;
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
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
