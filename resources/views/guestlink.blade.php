@php
    use Illuminate\Support\Facades\Session;
@endphp
@if (Session::get('is_guest_link'))
    <x-filament::link :href="route('continue_as_guest')" color="success" icon="heroicon-o-arrow-right" size="5xl" weight="bold">
        Continue as Guest
    </x-filament::link>
@endif
