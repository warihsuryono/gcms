<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        <center>
            <div style="height:250px;width:100%;margin:10px;">
                <div id="map" wire:ignore class="w-full h-full" style="z-index:9;"></div>
            </div>
        </center>
        {{ $this->form }}
        <center>
            @for ($photo_id = 1; $photo_id <= 5; $photo_id++)
                <br>
                <x-filament::button class="mt-3" icon="heroicon-m-camera" onclick="photo{{ $photo_id }}.click()">Take
                    Photo {{ $photo_id }}</x-filament::button>
                <input type="file" wire:model="photo{{ $photo_id }}" id="photo{{ $photo_id }}" class="hidden"
                    accept="image/*;capture=camera"
                    oncancel="notification_message.value = 'Canceled';$('#btnNotification').click();">

                @if ($showphoto[$photo_id])
                    <div style="margin:10px;" class="w-full rounded-full">
                        <p class="text-gray-400 text-center my-0" wordwrap>Preview</p>
                        <img width="150px" height="220px" style="object-fit: cover;" class="rounded"
                            src="{{ $previewphoto[$photo_id] }}" alt="Photo{{ $photo_id }}">
                    </div>
                @endif
            @endfor
        </center>
        <x-filament::button type="submit">
            Save
        </x-filament::button>
    </form>
    <input type='hidden' id='notification_message' value=''>
    <button id='btnNotification' wire:click="callNotification(notification_message.value)" class="hidden"></button>
</x-filament-panels::page>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"
        integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"
        integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7/turf.min.js"></script>
    <script>
        let marker, circle, circle2, lat, lon, featureGroup, map;

        $(document).ready(function() {
            setTimeout(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    setTimeout(() => {
                        $("#notification_message").val(
                            "You must allow location access to use this feature!");
                        $("#btnNotification").click();
                    }, 3000);
                }
            }, 1000);
        });

        function showPosition(position) {
            $('#map').html('');
            lat = position?.coords?.latitude;
            lon = position?.coords?.longitude;
            Livewire.find('{{ $this->getId() }}').set('lon', lon);
            Livewire.find('{{ $this->getId() }}').set('lat', lat);
            map = L.map('map').setView([lat, lon], 17);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; GCMS'
            }).addTo(map);
            marker = L.marker([lat, lon]).addTo(map);
        }
    </script>
@endpush
