<?php

namespace App\Filament\Resources\GameZoneResource\Pages;

use App\Filament\Resources\GameZoneResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameZones extends ListRecords
{
    protected static string $resource = GameZoneResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}