<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Dashboard;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\DashboardResource\Pages;

class DashboardResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Dashboard::class;
    protected static ?string $routename = 'dashboards';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')->directory('dashboard_images')->image()->imageEditor(),
                Forms\Components\TextInput::make('tagline')->maxLength(255),
                Forms\Components\TextInput::make('running_text_1')->maxLength(255),
                Forms\Components\TextInput::make('running_text_2')->maxLength(255),
                Forms\Components\TextInput::make('running_text_3')->maxLength(255),
                Forms\Components\TextInput::make('running_text_4')->maxLength(255),
                FileUpload::make('background')->directory('dashboard_images')->image()->imageEditor(),
                FileUpload::make('widget_1')->directory('dashboard_images')->image()->imageEditor(),
                Forms\Components\TextInput::make('widget_1_top')->numeric()->default(0),
                Forms\Components\TextInput::make('widget_1_left')->numeric()->default(0),
                FileUpload::make('widget_2')->directory('dashboard_images')->image()->imageEditor(),
                Forms\Components\TextInput::make('widget_2_top')->numeric()->default(0),
                Forms\Components\TextInput::make('widget_2_left')->numeric()->default(0),
                FileUpload::make('widget_3')->directory('dashboard_images')->image()->imageEditor(),
                Forms\Components\TextInput::make('widget_3_top')->numeric()->default(0),
                Forms\Components\TextInput::make('widget_3_left')->numeric()->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('tagline'),
                Tables\Columns\TextColumn::make('running_text_1'),
                Tables\Columns\TextColumn::make('running_text_2'),
                Tables\Columns\TextColumn::make('running_text_3')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('running_text_4')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('background')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('widget_1')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('widget_1_top')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('widget_1_left')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('widget_2')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('widget_2_top')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('widget_2_left')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('widget_3')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('widget_3_top')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('widget_3_left')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false)
            ->actions([EditAction::make()->iconButton()], ActionsPosition::BeforeColumns);
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
            'index' => Pages\ListDashboards::route('/'),
            'edit' => Pages\EditDashboard::route('/{record}/edit'),
            'show' => Pages\ShowDashboard::route('/show'),
        ];
    }
}
