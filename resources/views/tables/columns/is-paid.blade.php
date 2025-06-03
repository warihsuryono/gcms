<div>
    @php
        $colors = ['0' => 'danger', '1' => 'success'];
        $labels = ['0' => 'Unpaid', '1' => 'Paid'];
    @endphp
    <x-filament::badge color="{{ $colors[$getRecord()->is_paid] }}">{{ $labels[$getRecord()->is_paid] }}
    </x-filament::badge>
</div>
