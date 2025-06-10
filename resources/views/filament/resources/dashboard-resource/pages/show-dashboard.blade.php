@php
    use App\Models\Field;
@endphp

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
                        <th scope="col" class="px-6 py-3">Division</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Time</th>
                        <th scope="col" class="px-6 py-3">Field</th>
                        <th scope="col" class="px-6 py-3">Works</th>
                    </tr>
                </thead>
                <tbody id="work-order-list">
                    @foreach ($work_orders as $work_order)
                        @php
                            $fields = '';
                            foreach (json_decode($work_order->field_ids) as $field_id) {
                                $fields .= Field::find($field_id)->name . '<br>';
                            }
                        @endphp
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $work_order->division->name }}</td>
                            <td class="px-6 py-4">{{ date('d M Y', strtotime($work_order->work_start)) }}</td>
                            <td class="px-6 py-4">{{ date('H:i', strtotime($work_order->work_start)) }} -
                                {{ $work_order->work_end > $work_order->work_start ? date('H:i', strtotime($work_order->work_end)) : 'Done' }}
                            </td>
                            <td class="px-6 py-4">{!! $fields !!}</td>
                            <td class="px-6 py-4">{!! $work_order->works !!}</td>
                        </tr>
                    @endforeach
                    @for ($i = $work_orders->count() + 1; $i <= 10 - count($work_orders); $i++)
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <div style="position:absolute;bottom:800px;">
        <marquee direction="left" scrollamount="5" behavior="slide">Teks bergerak ke kanan dan berhenti</marquee>
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
        @if (count($work_orders) > 9)
            animateScroll();
        @endif
    });
</script>
