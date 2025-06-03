<?php

namespace App\Filament\Resources\ReimbursementResource\RelationManagers;

use App\Filament\Resources\ReimbursementResource;
use App\Models\FollowupOfficer;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\Reimbursement;
use App\Models\ReimbursementDetail;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\Concerns\InteractsWithRecord as ConcernsInteractsWithRecord;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Auth;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('transaction_at')->required(),
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
        if(Auth::user()->id == $this->getOwnerRecord()->user_id) {
            $headerActions = [
                Tables\Actions\CreateAction::make()
                ->after(function (ReimbursementDetail $detail, Component $livewire) {
                    $total = ReimbursementDetail::where('reimbursement_id', $detail->reimbursement_id)->sum('nominal');
                    Reimbursement::find($detail->reimbursement_id)->update(['total' => $total]);
                    $livewire->dispatch('refreshReimbursement');
                }),
            ];
            $actions = [
                Tables\Actions\EditAction::make()->iconButton()
                    ->after(function (ReimbursementDetail $detail, Component $livewire) {
                        $total = ReimbursementDetail::where('reimbursement_id', $detail->reimbursement_id)->sum('nominal');
                        Reimbursement::find($detail->reimbursement_id)->update(['total' => $total]);
                        $livewire->dispatch('refreshReimbursement');
                    }),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ];
        }
        return $table
            ->recordTitleAttribute('details')
            ->columns([
                TextColumn::make('transaction_at')->dateTime("d-m-Y")->sortable(),
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
