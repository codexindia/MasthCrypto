<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\MiningSession;
class AllCount extends BaseWidget
{
    protected function getStats(): array
    {
        $user_count = User::count();
        $activeUserCount = MiningSession::where('created_at', '>=', now()->subHours(24))
        ->distinct('user_id')
        ->count('user_id');
        $TotalClicks = MiningSession::whereDate('created_at', Carbon::today())
        ->count();
        return [
            Stat::make('Total Users',$user_count)
            ->description('increase in Users')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0]),
            
            Stat::make('Active User ',$activeUserCount)
            ->description('Last Mined In 24 Hours')
            //->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('danger'),
           

            Stat::make('Total Clicks',$TotalClicks)
            ->description('increase in Clicks')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0]),
            Stat::make('Coin Mined Today','10k')
            ->description('increase in Mining')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([2,5,2,1,5,0])
        ];
    }
}
