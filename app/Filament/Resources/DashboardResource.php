<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Dashboard;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Forms\Components\Section;
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
                Section::make('Main')->schema([
                    FileUpload::make('logo')->directory('dashboard_images')->image()->imageEditor(),
                    Forms\Components\TextInput::make('tagline')->maxLength(255),
                    FileUpload::make('background')->directory('dashboard_images')->image()->imageEditor(),
                ]),
                Section::make('Running Text')->schema([
                    Forms\Components\TextInput::make('running_text_1')->maxLength(255)->label('Running Text'),
                ]),
                Section::make('Widgets')->schema([
                    FileUpload::make('widget_1')->directory('dashboard_images')->image()->imageEditor()->label('Widget '),
                    Section::make('Position (px)')->schema([
                        Forms\Components\TextInput::make('widget_1_top')->numeric()->default(0)->label('Top'),
                        Forms\Components\TextInput::make('widget_1_left')->numeric()->default(0)->label('Left'),
                    ])->columns(2),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('tagline'),
                Tables\Columns\TextColumn::make('running_text_1')->label('Running Text'),
                ImageColumn::make('background'),
                ImageColumn::make('widget_1')->label('Widget'),
                Tables\Columns\TextColumn::make('widget_1_top')->label('Widget Top (px)')->alignRight(),
                Tables\Columns\TextColumn::make('widget_1_left')->label('Widget Left (px)')->alignRight(),
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
