@php
    use App\Filament\Resources\WorkOrderResource\Widgets\WorkOrdersFilters;
    use App\Filament\Resources\WorkOrderResource\Widgets\WorkOrdersChart;
@endphp

<x-filament-panels::page>
    @livewire(WorkOrdersFilters::class)
    @livewire(WorkOrdersChart::class)
</x-filament-panels::page>
