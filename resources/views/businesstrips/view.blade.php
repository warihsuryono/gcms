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
        @if (!$this->getRecord()->is_approved && $im_approve_officer)
            <x-filament::button class='h-10' wire:click="approve({{ $this->getRecord()->id }})">
                Approve
            </x-filament::button>
        @endif
        @if ($this->getRecord()->is_approved)
            <div>
                <div>Approved By,</div>
                <img style='height:100px' src='{{ asset('storage/' . $this->getRecord()->approvedBy->signature) }}' />
                <div>{{ $this->getRecord()->approvedBy->name }}</div>
            </div>
        @endif

        @if (!$this->getRecord()->is_acknowledge && $im_acknowledge_officer)
            <x-filament::button class='h-10' wire:click="acknowledge({{ $this->getRecord()->id }})">
                Acknowledge
            </x-filament::button>
        @endif
        @if ($this->getRecord()->is_acknowledge)
            <div>
                <div>Acknowledge By,</div>
                <img style='height:100px' src='{{ asset('storage/' . $this->getRecord()->AcknowledgeBy->signature) }}' />
                <div>{{ $this->getRecord()->AcknowledgeBy->name }}</div>
            </div>
        @endif
        <div></div>
    </div>
</x-filament-panels::page>
