<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AllCount extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users','10k')
            ->description('increase in Users')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0]),
            
            Stat::make('Total Users','10k')
            ->description('increase in Users')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0]),

            Stat::make('Total Users','10k')
            ->description('increase in Users')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0]),
            Stat::make('Total Users','10k')
            ->description('increase in Users')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0])
        ];
    }
}
