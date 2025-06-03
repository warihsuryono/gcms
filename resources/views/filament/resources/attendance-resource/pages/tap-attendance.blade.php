    <x-filament-panels::page>
        <form action="/{{ env('PANEL_PATH') }}/attendances/tap/save" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="lon" id="lon">
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="attendancetype" id="attendancetype">
            <input type="submit" id="submit_button" class="hidden">
            <center>
                <x-filament::button icon="heroicon-m-camera" onclick="photo.click()">Take Photo</x-filament::button>
                <input type="file" onchange="loadPhoto()" name="photo" id="photo" class="hidden"
                    accept="image/*;capture=camera"
                    oncancel="notification_message.value = 'Canceled';$('#btnNotification').click();">

                <div style="margin:10px;" class="w-full rounded-full hidden" id="upload">
                    <p class="text-gray-400 text-center my-0" wordwrap>Preview</p>
                    <img width="150px" height="220px" style="object-fit: cover;" class="rounded" id="previewphoto"
                        alt="Photo">
                </div>

                <div style="height:200px;width:100%;margin:10px;">
                    <div id="map" wire:ignore class="w-full h-full" style="z-index:9;"></div>
                </div>
                <div style="margin-bottom:10px;">{{ Auth::user()->name }}</div>
                <div style="margin-bottom:10px;">{{ date('D, d M Y') }}</div>
                <div style="margin-bottom:10px;" id="liveclock"></div>
                <div style="margin-bottom:10px;" class="hidden" id="tap_button">
                    <x-filament::button onclick="tap_clicked('in');"
                        class="{{ $isInExists ? 'hidden' : '' }}">Masuk</x-filament::button>
                    <x-filament::button onclick="tap_clicked('out');" color="danger">Pulang</x-filament::button>
                </div>
                <div style="margin-bottom:10px;" class="hidden" id="wait_alert">Please wait....</div>
            </center>
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
                clockUpdate();
                getLocation();
            });

            function clockUpdate() {
                var date = new Date();
                $('#liveclock').text(date.toLocaleTimeString());
                setInterval(clockUpdate, 1000);
            }

            function getLocation() {

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    notification_message.value = "Anda harus menyetujui akses lokasi untuk melakukan absensi!";
                    $("#btnNotification").click();
                }
            }

            function showPosition(position) {
                $('#map').html('');
                lat = position?.coords?.latitude;
                lon = position?.coords?.longitude;
                $('#lon').val(lon);
                $('#lat').val(lat);
                map = L.map('map').setView([lat, lon], 13);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; GWS Workspace'
                }).addTo(map);
                marker = L.marker([lat, lon]).addTo(map);
            }

            function loadPhoto() {
                try {
                    let input = $('#photo')[0];
                    let file = input?.files[0];
                    let ext = (/[.]/.exec(file?.name)) ? /[^.]+$/.exec(file?.name) : undefined;
                    if (ext == 'jpg' || ext == 'jpeg') {
                        if (file != undefined) {
                            let preview = URL.createObjectURL(file);
                            $('#previewphoto').attr('src', preview);
                            $('#upload').removeClass('hidden');
                            $('#tap_button').removeClass('hidden');
                        }
                    } else {
                        notification_message.value = "File extension photo must be jpg or jpeg";
                        $("#btnNotification").click();
                    }
                } catch (err) {
                    notification_message.value = "Error : " + err.toString();
                    $("#btnNotification").click();
                }
            }

            function tap_clicked(in_out) {
                $('#wait_alert').removeClass('hidden');
                $('#attendancetype').val(in_out);
                $('#submit_button').click();
                $('#tap_button').addClass('hidden');
            }
        </script>
    @endpush
