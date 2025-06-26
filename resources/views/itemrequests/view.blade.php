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

        @if ($is_stock_visible)
            <x-filament::button class='h-10' wire:click="create_purchase_order()" color="success">
                Create Purchase Order
            </x-filament::button>
        @endif

        @if (@$this->getRecord()->work_order_id > 0)
            <x-filament::button class='h-10' wire:click="work_order()" color="primary">
                Show Work Order
            </x-filament::button>
        @endif

        @if ($is_stock_visible && $record->is_issued == 0)
            <x-filament::button class='h-10' wire:click="item_request_issued()" color="success">
                Item Request Issued
            </x-filament::button>
        @endif
        @if ($record->is_issued)
            <div>
                <div>Issued By,</div>
                <br><br>
                <div>{{ @$this->getRecord()->issuedBy->name }}</div>
            </div>
        @endif

        @if ($is_can_received && $record->is_received == 0)
            <x-filament::button class='h-10' wire:click="item_request_received()" color="success">
                Item Request Received
            </x-filament::button>
        @endif
        @if ($record->is_received)
            <div>
                <div>Received By,</div>
                <br><br>
                <div>{{ @$this->getRecord()->receivedBy->name }}</div>
            </div>
        @endif

        <div></div>
    </div>
</x-filament-panels::page>
