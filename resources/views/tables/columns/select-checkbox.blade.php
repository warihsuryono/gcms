<div>
    <x-filament::input.checkbox wire:model='checkdetails.{{ $getRecord()->id }}' value="1"
        x-on:click="showButtonSelectedToAction = true"></x-filament::input>
</div>
