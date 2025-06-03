<div>
    <x-filament::icon-button tag="a" icon="heroicon-o-chevron-double-up"
        href="{{ App::make('url')->to(env('PANEL_PATH') . '/report-templates/moveup/' . $getRecord()->id) }}"></x-filament::icon-button>

    <x-filament::icon-button tag="a" icon="heroicon-o-chevron-double-down"
        href="{{ App::make('url')->to(env('PANEL_PATH') . '/report-templates/movedown/' . $getRecord()->id) }}"><x-slot
            name="badge">
            {{ $getState() }}
        </x-slot></x-filament::icon-button>
</div>
