<?php

namespace App\Filament\Resources\FuelConsumptionResource\Widgets;

use App\Models\ItemType;
use App\Models\FuelConsumption;
use Livewire\Attributes\On;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class FuelConsumptionsChart extends ApexChartWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $loadingIndicator = 'Loading...';

    public string $consumption_from = "";
    public string $consumption_until = "";
    public $item_type_id;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'fuelConsumptionsChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Fuel Consumptions Chart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    public function getSubheading(): string
    {
        if (!$this->consumption_from) $this->consumption_from = date('Y') . '-01-01';
        if (!$this->consumption_until) $this->consumption_until = date('Y') . '-12-31';
        if ($this->consumption_from && $this->consumption_until)
            return " (" . date("d F Y", strtotime($this->consumption_from)) . " - " . date("d F Y", strtotime($this->consumption_until)) . ")";
        else return '';
    }

    public function getHeading(): string
    {
        if ($this->item_type_id)
            return "ItemType : " . ItemType::find($this->item_type_id)->name;
        else return '';
    }

    public function getFooter(): string
    {
        $return = '';
        if ($this->item_type_id)
            $return = "ItemType : " . ItemType::find($this->item_type_id)->name;
        if ($this->consumption_from && $this->consumption_until)
            $return .= " (" . date("d F Y", strtotime($this->consumption_from)) . " - " . date("d F Y", strtotime($this->consumption_until)) . ")";
        return $return;
    }

    #[On('updateFilter')]
    public function updateFilter(string $consumption_from, string $consumption_until, $item_type_id = 0): void
    {
        $this->consumption_from = $consumption_from;
        $this->consumption_until = $consumption_until;
        $this->item_type_id = $item_type_id ?? 0;
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

        if ($this->consumption_from && $this->consumption_until) {
            if ($this->item_type_id > 0) $ItemTypes = ItemType::where('id', $this->item_type_id)->get();
            else $ItemTypes = ItemType::where('id', '<', 3)->get();

            $consumption_at = $this->consumption_from;
            while (substr($consumption_at, 0, 7) <= substr($this->consumption_until, 0, 7)) {
                $categories[] = substr($consumption_at, 0, 7);
                $consumption_at = date("Y-m", strtotime("+1 month", strtotime($consumption_at)));
            }

            foreach ($ItemTypes as $key => $ItemType) {
                $data = [];
                $label = $ItemType->name;
                foreach ($categories as $consumption_at) $data[] = FuelConsumption::where('item_type_id', $ItemType->id)->where('consumption_at', 'LIKE', $consumption_at . '%')->sum('quantity');
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
