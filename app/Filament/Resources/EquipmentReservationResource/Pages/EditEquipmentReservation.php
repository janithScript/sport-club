<?php

namespace App\Filament\Resources\EquipmentReservationResource\Pages;

use App\Filament\Resources\EquipmentReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentReservation extends EditRecord
{
    protected static string $resource = EquipmentReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}