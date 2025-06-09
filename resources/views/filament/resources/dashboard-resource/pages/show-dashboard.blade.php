<x-filament-panels::page style="background-image:url('/storage/{{ $dashboard->background }}');" class="bg-cover">
    <div>
        <div id="scrollable-table-container" class="h-full overflow-y-auto" style="max-height: 550px">
            <table class="table table-auto scroll-smooth">
                <thead class="sticky top-0">
                    <tr>
                        <th>No</th>
                        <th>Field</th>
                        <th>Time</th>
                        <th>Division</th>
                        <th>Work</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="work-order-list">
                    @for ($index = 0; $index < 30; $index++)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>Field {{ $index + 1 }}</td>
                            <td>{{ date('H:i', strtotime('-' . $index % 24 . ' hours')) }} - Done</td>
                            <td>Division {{ $index + 1 }}</td>
                            <td>Work {{ $index + 1 }}</td>
                            <td>Description for Field {{ $index + 1 }}</td>
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
