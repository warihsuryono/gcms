@php
    use App\Models\Field;
@endphp
<script>
    function openModal() {
        @this.dispatch('open-modal', {
            id: 'modal-division-filter'
        });
    }
</script>
<x-filament-panels::page style="background-image:url('/storage/{{ $dashboard->background }}');" class="bg-cover">
    <img src="/storage/{{ $dashboard->widget_1 }}"
        style="position:absolute;width:160px;top:{{ $dashboard->widget_1_top }}px;left:{{ $dashboard->widget_1_left }}px"
        alt="Widget-1">
    <div class="p-2">
        <div style="position: relative; width: 100%;">
            <div style="position:relative;width:120px;top:-30px;left: 50%; transform: translateX(-50%);">
                <img src="/storage/{{ $dashboard->logo }}">
            </div>
            <h3 class="text-center text-xl font-bold" style="position:relative;top:-30px;">{{ $dashboard->tagline }}</h3>
        </div>
        <div class="flex items-center justify-between">
            <h1 class="font-bold">Work Orders</h1>
        </div>
        <div id="scrollable-table-container"
            class="relative shadow-md sm:rounded-lg h-full overflow-y-auto overflow-x-auto"
            style="max-height: 380px;max-width:900px;">
            <table class="table table-auto scroll-smooth text-sm text-left rtl:text-right text-black opacity-70">
                <thead class="sticky top-0 text-xs text-black uppercase bg-white border-b-2">
                    <tr>
                        <th scope="col" class="px-2 py-2">No</th>
                        <th scope="col" class="px-2 py-2 flex" nowrap>Division <x-heroicon-o-funnel
                                class="w-4 h-4 text-gray-500" onclick="openModal()" /></th>
                        <th scope="col" class="px-2 py-2">Date</th>
                        <th scope="col" class="px-2 py-2">Time</th>
                        <th scope="col" class="px-2 py-2">Field</th>
                        <th scope="col" class="px-2 py-2">Works</th>
                    </tr>
                </thead>
                <tbody id="work-order-list">
                    @foreach ($urgent_work_orders as $work_order)
                        <tr class="odd:bg-red-700 even:bg-red-400 border-b border-gray-200">
                            <td class="px-2 py-2">{{ $loop->iteration }}</td>
                            <td nowrap class="px-2 py-2">{{ $work_order->division->name }}</td>
                            <td nowrap class="px-2 py-2">{{ date('d M Y', strtotime($work_order->work_at)) }}</td>
                            <td nowrap class="px-2 py-2">Done</td>
                            <td nowrap class="px-2 py-2">{{ @$work_order->field->name }}</td>
                            <td class="px-2 py-2">{!! $work_order->works !!}</td>
                        </tr>
                    @endforeach
                    @foreach ($work_orders as $work_order)
                        @php
                            $fields = '';
                            foreach (json_decode($work_order->field_ids) as $key => $field_id) {
                                $fields .= @Field::find($field_id)->name . ', ';
                                if ($key % 2 == 0 && $key > 0) {
                                    $fields .= '<br>';
                                }
                            }

                            $fields = rtrim($fields, ', ');
                        @endphp
                        <tr class="odd:bg-white even:bg-gray-400 border-b border-gray-200">
                            <td class="px-2 py-2">{{ $loop->iteration }}</td>
                            <td nowrap class="px-2 py-2">{{ $work_order->division->name }}</td>
                            <td nowrap class="px-2 py-2">{{ date('d M Y', strtotime($work_order->work_start)) }}</td>
                            <td nowrap class="px-2 py-2">{{ date('H:i', strtotime($work_order->work_start)) }} -
                                {{ $work_order->work_end > $work_order->work_start ? date('H:i', strtotime($work_order->work_end)) : 'Done' }}
                            </td>
                            <td class="px-2 py-2">{!! $fields !!}</td>
                            <td class="px-2 py-2">{!! $work_order->works !!}</td>
                        </tr>
                    @endforeach
                    @for ($i = $work_orders->count() + 1; $i <= 10 - count($work_orders); $i++)
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td class="px-2 py-2"></td>
                            <td class="px-2 py-2"></td>
                            <td class="px-2 py-2"></td>
                            <td class="px-2 py-2"></td>
                            <td class="px-2 py-2"></td>
                            <td class="px-2 py-2"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="mt-1 text-center text-gray-600 bg-lime-500 pt-1 pb-1 rounded-lg shadow-md flex items-center">
            <div class="text-white font-bold text-lg w-96 mr-2 ml-2 bg-blue-600">
                <span>{{ date('l') . ', ' . date('d F Y') }}</span>
            </div>
            <div class="w-full font-bold pt-1">
                <marquee direction="left" scrollamount="5" behavior="scroll">{{ $dashboard->running_text_1 }}</marquee>
            </div>
            <div class="text-white font-bold text-lg w-32 mr-2 ml-2 bg-blue-600">
                <span id="clock"></span>
            </div>
        </div>
    </div>

    <x-filament::modal id="modal-division-filter">
        <form class="block mt-1">
            <x-filament::fieldset>
                <x-slot name="label">
                    Division
                </x-slot>
                <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
                    <div>

                        <div class="flex items-center">
                            <input id="division-radio-1" type="radio" value="0" name="division-radio" checked
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                onchange="window.location.href = '{{ request()->url() }}?division_id=0';">
                            <label for="division-radio-1" class="ms-2 text-sm font-medium text-gray-900">All
                                Division</label>
                        </div>

                        @foreach ($divisions as $division)
                            <div class="flex items-center">
                                <input id="division-radio-2" type="radio" value="{{ $division->name }}"
                                    {{ request()->get('division_id') == $division->id ? 'checked' : '' }}
                                    name="division-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                    onchange="window.location.href = '{{ request()->url() }}?division_id={{ $division->id }}';">
                                <label for="division-radio-2"
                                    class="ms-2 text-sm font-medium text-gray-900">{{ $division->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-filament::fieldset>
        </form>
    </x-filament::modal>
</x-filament-panels::page>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var scrolling = false;
        var scroll = -1;
        var delay = 50;

        function animateScroll() {
            if (scroll >= $('#work-order-list').height() - ($('#scrollable-table-container').height() / 2))
                scroll = -1;
            $('#scrollable-table-container').scrollTop(scroll);
            scroll++;
            if (scroll == 0) delay = 3000;
            else delay = 50;
            setTimeout(animateScroll, delay);
        }

        function clock_tick() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const currentTime = `${hours}:${minutes}:${seconds}`;

            $("#clock").html(currentTime);
            setTimeout(clock_tick, 1000);
        }

        animateScroll();
        clock_tick();
    });
</script>
