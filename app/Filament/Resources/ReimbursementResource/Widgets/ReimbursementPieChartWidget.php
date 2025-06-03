<?php

namespace App\Filament\Resources\ReimbursementResource\Widgets;

use App\Models\Reimbursement;
use Filament\Widgets\ChartWidget;

class ReimbursementPieChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pie Chart Paid Reimbursement';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        return [
            'labels' => [
                'Paid',
                'Unpaid',
            ],
            'datasets' => [
                [
                    'label' => 'Reimbursement',
                    'data' => [
                        Reimbursement::where('is_paid', 1)->count(),
                        Reimbursement::where('is_paid', 0)->count(),
                    ],
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)', // success
                        'rgba(255, 99, 132, 0.2)', // danger
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192)',
                        'rgba(255, 99, 132)', // danger
                    ],
                    'borderWidth' => 1,
                    'hoverOffset' => 4,
                ],
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
