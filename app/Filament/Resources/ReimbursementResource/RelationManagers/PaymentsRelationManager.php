<?php

namespace App\Filament\Resources\ReimbursementResource\RelationManagers;

use App\Models\FollowupOfficer;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\Reimbursement;
use App\Models\ReimbursementPayment;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Auth;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('paid_at')->required(),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nominal')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('Rp. '),
                FileUpload::make('attachment')
                    ->directory('reimbursements')
                    ->image()->imageEditor(),
            ]);
    }

    public function table(Table $table): Table
    {
        $headerActions = [];
        $actions = [];
        $reimbursementPayments = FollowupOfficer::where('action', 'reimbursement-payment')->get()->pluck('user_id')->toArray();
        if (in_array(Auth::user()->id, $reimbursementPayments)) {
            $headerActions = [
                Tables\Actions\CreateAction::make(),
            ];
            $actions = [
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ];
        }
        return $table
            ->recordTitleAttribute('payment')
            ->columns([
                TextColumn::make('paid_at')->dateTime("d-m-Y")->sortable(),
                TextColumn::make('description'),
                TextColumn::make('nominal')->money('Rp. '),
                ImageColumn::make('attachment')
            ])
            ->filters([
                //
            ])
            ->headerActions($headerActions)
            ->actions($actions, ActionsPosition::BeforeColumns)
            ->paginated(false);
    }
}
