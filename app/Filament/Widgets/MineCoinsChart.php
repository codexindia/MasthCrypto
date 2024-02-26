<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class MineCoinsChart extends ChartWidget
{
    protected static ?string $heading = 'Last 7 Day Coins Mined';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Total Coins Mined In Days',
                    'data' => [1, 10, 5, 2, 21, 32, 45],
                   
                ],
            ],
            'labels' => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
