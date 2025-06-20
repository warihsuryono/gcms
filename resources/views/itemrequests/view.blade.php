<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
            :page-class="static::class" />
    @endif

    <div class="flex justify-between">
        <div></div>
        @if (@$this->getRecord()->work_order_id > 0)
            <x-filament::button class='h-10' wire:click="work_order()" color="primary">
                Show Work Order
            </x-filament::button>
        @endif
        <div></div>
    </div>
</x-filament-panels::page>
