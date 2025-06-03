<x-filament::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}


        <div class="relative overflow-x-auto">
            <h3>Privileges:</h3>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th></th>
                        <th scope="col" class="px-6 py-3">
                            Add
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Edit
                        </th>
                        <th scope="col" class="px-6 py-3">
                            View
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Delete
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-3 py-4 font-bold text-gray-700 whitespace-nowrap dark:text-white">
                                <x-filament::input.checkbox wire:model='mainmenu.{{ $menu->id }}'
                                    wire:click="checkbox_toggle({{ $menu->id }})"
                                    value="15"></x-filament::input>
                                    {{ $menu->name }}
                            </th>
                            <th scope="row"></th>
                            <th scope="row"></th>
                            <th scope="row"></th>
                            <th scope="row"></th>

                        </tr>
                        @foreach ($menu->childMenu as $childMenu)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-thin text-gray-700 whitespace-nowrap dark:text-white">
                                    <x-filament::input.checkbox wire:model='childmenu.{{ $childMenu->id }}'
                                        wire:click="checkbox_toggle({{ $childMenu->id }})"
                                        value="15"></x-filament::input> {{ $childMenu->name }}
                                </th>
                                <th scope="row" class="px-6 py-3">
                                    <x-filament::input.checkbox wire:model='childmenu_add.{{ $childMenu->id }}'
                                        value="1"></x-filament::input>
                                </th>
                                <th scope="row" class="px-6 py-3">
                                    <x-filament::input.checkbox wire:model='childmenu_edit.{{ $childMenu->id }}'
                                        value="2"></x-filament::input>
                                </th>
                                <th scope="row" class="px-6 py-3">
                                    <x-filament::input.checkbox wire:model='childmenu_view.{{ $childMenu->id }}'
                                        value="4"></x-filament::input>
                                </th>
                                <th scope="row" class="px-6 py-3">
                                    <x-filament::input.checkbox wire:model='childmenu_delete.{{ $childMenu->id }}'
                                        value="8"></x-filament::input>
                                </th>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
    <br>
</x-filament::page>
