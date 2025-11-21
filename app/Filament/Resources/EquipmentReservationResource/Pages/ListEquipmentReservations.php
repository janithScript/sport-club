<?php

namespace App\Filament\Resources\EquipmentReservationResource\Pages;

use App\Filament\Resources\EquipmentReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquipmentReservations extends ListRecords
{
    protected static string $resource = EquipmentReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}