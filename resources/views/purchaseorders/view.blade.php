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
        @if ($this->getRecord()->is_approved)
            <div>
                <div>Approved By,</div>
                <br><br>
                <div>{{ $this->getRecord()->approvedBy->name }}</div>
            </div>
        @endif
        @if ($this->getRecord()->is_sent)
            <div>
                <div>Sent By,</div>
                <br><br>
                <div>{{ $this->getRecord()->sentBy->name }}</div>
            </div>
        @endif
        @if ($this->getRecord()->is_closed)
            <div>
                <div>Closed By,</div>
                <br><br>
                <div>{{ $this->getRecord()->closedBy->name }}</div>
            </div>
        @endif
        <div></div>
    </div>
</x-filament-panels::page>
