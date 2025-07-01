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
        @if ($record->is_approved)
            <div>
                <div>Approved By,</div>
                <br><br>
                <div>{{ @$this->getRecord()->approvedBy->name }}</div>
            </div>
        @endif
        <div></div>
    </div>
</x-filament-panels::page>
