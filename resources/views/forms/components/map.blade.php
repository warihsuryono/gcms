<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <center>
            <div style="height:250px;width:100%;margin:10px;">
                <div id="map" wire:ignore class="w-full h-full" style="z-index:9;"></div>
            </div>
        </center>
    </div>
</x-dynamic-component>

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
        let lat, lon, map;

        $(document).ready(function() {
            setTimeout(() => {
                $('#map').html('');
                lat = {{ $getRecord()->lat }};
                lon = {{ $getRecord()->lon }};
                map = L.map('map').setView([lat, lon], 17);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; GCMS'
                }).addTo(map);
                marker = L.marker([lat, lon]).addTo(map);
            }, 1000);
        });
    </script>
@endpush
