<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class TableWindrose extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    // public function mount($dispersionId){
    //     $this->dispersionId = $dispersionId;
    // }
    public function render()
    {
        return view('livewire.table-windrose');
    }
}
