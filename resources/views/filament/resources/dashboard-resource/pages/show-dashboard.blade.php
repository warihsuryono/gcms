<x-filament-panels::page style="background-image:url('/storage/{{ $dashboard->background }}');" class="bg-cover">
    <img src="/storage/{{ $dashboard->widget_1 }}"
        style="position:absolute;width:160px;top:{{ $dashboard->widget_1_top }}px;left:{{ $dashboard->widget_1_left }}px"
        alt="Widget-1">
    <div class="p-2">
        <img src="/storage/{{ $dashboard->logo }}" style="position:relative;width:120px;top:-30px;" alt="Logo">
        <h3 class="text-xl font-bold" style="position:relative;top:-30px;">{{ $dashboard->tagline }}</h3>

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Work Orders</h1>
        </div>
        <div id="scrollable-table-container"
            class="relative shadow-md sm:rounded-lg h-full overflow-y-auto overflow-x-auto" style="max-height: 350px">
            <table
                class="table table-auto scroll-smooth text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Field</th>
                        <th scope="col" class="px-6 py-3">Time</th>
                        <th scope="col" class="px-6 py-3">Division</th>
                        <th scope="col" class="px-6 py-3">Work</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                    </tr>
                </thead>
                <tbody id="work-order-list">
                    @for ($index = 0; $index < 30; $index++)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">Field {{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ date('H:i', strtotime('-' . $index % 24 . ' hours')) }} - Done
                            </td>
                            <td class="px-6 py-4">Division {{ $index + 1 }}</td>
                            <td class="px-6 py-4">Work {{ $index + 1 }}</td>
                            <td class="px-6 py-4">Description for Field {{ $index + 1 }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var $scroll = -1;
        var delay = 50;

        function animateScroll() {
            if ($scroll >= $('#work-order-list').height() - ($('#scrollable-table-container').height() / 2))
                $scroll = -1;
            $('#scrollable-table-container').scrollTop($scroll);
            $scroll++;
            if ($scroll == 0) delay = 3000;
            else delay = 50;

            setTimeout(animateScroll, delay);
        }
        animateScroll();
    });
</script>
