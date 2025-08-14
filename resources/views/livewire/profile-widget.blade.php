<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <img class="fi-avatar object-cover object-center fi-circular rounded-full h-10 w-10 fi-user-avatar"
                src="{{ $this->avatar }}" alt="Avatar of {{ $this->name }}">
            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Welcome
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->name }} <small>({{ Auth::user()->name }})</small>
                </p>
            </div>
            <div>
                @if ($this->is_employee)
                    <x-filament::button class="mb-2" color="warning" wire:click='goToTapAttendance'>
                        Attendance
                    </x-filament::button>
                    <x-filament::button class="mb-2" color="success" wire:click='goToPresenceSchedules'>
                        Presence Schedules
                    </x-filament::button>
                @endif
                @if (!$this->is_guest)
                    <x-filament::button class="mb-2" color="primary" wire:click='goToProfile'>
                        My Profile
                    </x-filament::button>
                @endif
                <x-filament::button class="mb-2" color="danger" wire:click='goToNewUrgentWorkOrder'>
                    Urgent Work Order
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
