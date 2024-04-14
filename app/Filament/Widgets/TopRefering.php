<?php

namespace App\Filament\Widgetss;


use App\Models\User;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\ReferData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class TopRefering extends BaseWidget
{
    public static function getPosition(): ?int
    {
        return 1;
    }
    public function getTableQuery():Builder
    {
        $topReferredUsers = ReferData::select('referred_to', DB::raw('count(*) as referral_count'))
            ->groupBy('referred_to')
            ->orderByDesc('referral_count')
            ->limit(10)
            ->get()
            ->pluck('referred_to');

        return User::whereIn('id', $topReferredUsers);
    }
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('name')
                ,
            Tables\Columns\TextColumn::make('email')
                ->label('email')
              ,
            Tables\Columns\BadgeColumn::make('referral_count')
                ->label('Referrals')
                ->color('success'),
        ];
    }
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
