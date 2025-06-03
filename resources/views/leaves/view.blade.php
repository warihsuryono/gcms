<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif
    <div class="flex justify-between">
        <div></div>
        @if (!$this->getRecord()->is_approved && $im_approve_officer)
            <x-filament::button class='h-10' color='success' icon='heroicon-o-check-circle' wire:click="approve()">
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

        @if (!$this->getRecord()->is_acknowledge && $im_acknowledge_officer && $this->getRecord()->is_approved == 1)
            <x-filament::button class='h-10' color='primary' icon='heroicon-o-document-check' wire:click="acknowledge()">
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