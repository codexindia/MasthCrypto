<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserCountry extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Country Wise Joining';
    public function getTopCountriesByUserCount(int $limit = 15): array
    {
        return User::selectRaw('c.name as country_name, COUNT(*) as total_users')
        ->join('country_infos as c', 'users.country_code', '=', 'c.dial_code')
        ->groupBy('c.name')
        ->orderByDesc('total_users')
        ->limit($limit)
        
        ->pluck('total_users', 'country_name')
        ->toArray();
    }
    protected function getData(): array
    {
        $topCountries = $this->getTopCountriesByUserCount();

        return [
            'datasets' => [
                [
                    'label' => 'Top Joining Country',
                    'data' => array_values($topCountries),
                ]
            ],
            'labels' => array_keys($topCountries),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
