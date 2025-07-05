<?php

namespace App\Filament\Resources\WorkOrderResource\Widgets;

use App\Models\Division;
use App\Models\WorkOrder;
use Livewire\Attributes\On;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class WorkOrdersChart extends ApexChartWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $loadingIndicator = 'Loading...';

    public string $work_start_from = "";
    public string $work_start_until = "";
    public int $division_id = 0;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'workOrdersChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Work Orders Chart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    public function getSubheading(): string
    {
        if (!$this->work_start_from) $this->work_start_from = date("Y-m-d", strtotime("first day of this month"));
        if (!$this->work_start_until) $this->work_start_until = date("Y-m-d", strtotime("last day of this month"));
        if ($this->work_start_from && $this->work_start_until)
            return " (" . date("d F Y", strtotime($this->work_start_from)) . " - " . date("d F Y", strtotime($this->work_start_until)) . ")";
        else return '';
    }

    public function getHeading(): string
    {
        if ($this->division_id)
            return "Division : " . Division::find($this->division_id)->name;
        else return '';
    }

    public function getFooter(): string
    {
        $return = '';
        if ($this->division_id)
            $return = "Division : " . Division::find($this->division_id)->name;
        if ($this->work_start_from && $this->work_start_until)
            $return .= " (" . date("d F Y", strtotime($this->work_start_from)) . " - " . date("d F Y", strtotime($this->work_start_until)) . ")";
        return $return;
    }

    #[On('updateFilter')]
    public function updateFilter(string $work_start_from, string $work_start_until, int $division_id): void
    {
        $this->work_start_from = $work_start_from;
        $this->work_start_until = $work_start_until;
        $this->division_id = $division_id;
    }

    protected function getOptions(): array
    {
        $color[0] = '#6366f1';
        $color[1] = '#63f166';
        $color[2] = '#f16366';
        $color[3] = '#6366f1';
        $color[4] = '#63f1f1';
        $color[5] = '#63ff66';
        $color[6] = '#f1f166';
        $color[7] = '#f166f1';

        $series = [];
        $colors = [];
        $data = [];
        $categories = [];
        $label = '';

        if ($this->work_start_from && $this->work_start_until) {
            if ($this->division_id > 0) $divisions = Division::where('id', $this->division_id)->get();
            else $divisions = Division::all();

            $work_start = $this->work_start_from;
            while ($work_start <= $this->work_start_until) {
                $categories[] = $work_start;
                $work_start = date("Y-m-d", strtotime("+1 day", strtotime($work_start)));
            }

            foreach ($divisions as $key => $division) {
                $data = [];
                $label = $division->name;
                foreach ($categories as $work_start) {
                    $whereRaw = "division_id = " . $division->id . " AND work_start LIKE '" . $work_start . "%'";
                    $data[] = WorkOrder::whereRaw($whereRaw)->count();
                }
                $colors[] = $color[$key];
                $series[] = ['name' => $label, 'data' => $data];
            }
            return [
                'chart' => [
                    'type' => 'line',
                    'height' => 400,
                ],
                'series' => $series,
                'xaxis' => [
                    'categories' => $categories,
                    'labels' => [
                        'style' => [
                            'colors' => '#9ca3af',
                            'fontWeight' => 300,
                        ],
                    ],
                ],
                'yaxis' => [
                    'labels' => [
                        'style' => [
                            'colors' => '#9ca3af',
                            'fontWeight' => 300,
                        ],
                    ],
                ],
                'colors' => $colors,
                'stroke' => [
                    'curve' => 'smooth',
                ],
            ];
        }
        return [];
    }
}
