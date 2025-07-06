@php
    use App\Filament\Resources\FuelConsumptionResource\Widgets\FuelConsumptionsFilters;
    use App\Filament\Resources\FuelConsumptionResource\Widgets\FuelConsumptionsChart;
@endphp

<x-filament-panels::page>
    @livewire(FuelConsumptionsFilters::class)
    @livewire(FuelConsumptionsChart::class)
</x-filament-panels::page>
