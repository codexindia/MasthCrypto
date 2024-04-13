<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\MiningSession;

class MineCoinsChart extends ChartWidget
{
    protected static ?string $heading = 'Last 7 Day Coins Mined';

    protected function getData(): array
    {
        $data = MiningSession::selectRaw('DATE(created_at) as date, sum(coin) as sum')
            ->whereDate('created_at', '>=', now()->startOfWeek())
            ->whereDate('created_at', '<=', now()->endOfWeek())
            ->groupBy('date')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('l');
            });
        $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $sum = array_fill(0, 7, 0);

        foreach ($data as $day => $sum) {
            $sum[array_search($day, $labels)] = $sum->sum;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Total Coins Mined',
                    'data' => $sum,
                ],
            ],
            'labels' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
