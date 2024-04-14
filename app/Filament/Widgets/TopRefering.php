<?php

namespace App\Filament\Widgets;

use App\Models\ReferData;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopRefering extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ReferData::query()
            )
            ->columns([
                TextColumn::make('user_id')
            ]);
    }
}
