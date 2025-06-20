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
        @if ($this->getRecord()->prev_work_order_id > 0)
            <x-filament::button class='h-10' wire:click="prev_work_order()" color="warning">
                << Previous Work Order </x-filament::button>
        @endif
        @if (@$this->getRecord()->item_request->id > 0)
            <x-filament::button class='h-10' wire:click="item_request()" color="primary">
                Show Item Request
            </x-filament::button>
        @endif
        @if (@$this->getRecord()->next_work_order->id > 0)
            <x-filament::button class='h-10' wire:click="next_work_order()" color="success">
                Next Work Order >>
            </x-filament::button>
        @endif
        <div></div>
    </div>
</x-filament-panels::page>
