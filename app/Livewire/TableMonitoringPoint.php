<?php

namespace App\Livewire;

use App\Models\DispersionResult;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TableMonitoringPoint extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $dispersionId;
    public function mount($dispersionId){
        $this->dispersionId = $dispersionId;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(DispersionResult::whereHas("monitoring_point", function($query){
                $query->where("dispersion_id", $this->dispersionId);
            }))
            ->columns([
                TextColumn::make("monitoring_point.name")
                    ->label("Titik pantau"),
                TextColumn::make("monitoring_point.latitude")
                    ->label("Latitude"),
                TextColumn::make("monitoring_point.longitude")
                    ->label("Longitude"),
                TextColumn::make("pollutant"),
                TextColumn::make("avertime")
                    ->suffix(function($record){
                        return (is_numeric($record->avertime)) ? " HOURS" : "";
                    }),
                TextColumn::make("rona_awal")
                    ->suffix("μg/m³"),
                TextColumn::make("besaran_dampak")
                    ->suffix("μg/m³"),
                TextColumn::make("rona_akhir")
                    ->suffix("μg/m³"),
                TextColumn::make("baku_mutu")
                    ->suffix("μg/m³"),
            ])
            ->defaultSort("pollutant")
            ->defaultGroup("pollutant")
            ->emptyStateHeading("Data Titik Pantau Tidak Ada")
            ->emptyStateDescription("Data titik pantau ditambahkan sebelum melakukan pemodelan pada halaman awal konfigurasi.")
            ->paginated(false);
    }

    public function render()
    {
        return view('livewire.table-monitoring-point');
    }
}
