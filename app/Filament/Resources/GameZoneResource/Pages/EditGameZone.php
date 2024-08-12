<?php

namespace App\Filament\Resources\GameZoneResource\Pages;

use App\Filament\Resources\GameZoneResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameZone extends EditRecord
{
    protected static string $resource = GameZoneResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}