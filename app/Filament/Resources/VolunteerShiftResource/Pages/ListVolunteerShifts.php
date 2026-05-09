<?php

namespace App\Filament\Resources\VolunteerShiftResource\Pages;

use App\Filament\Resources\VolunteerShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVolunteerShifts extends ListRecords
{
    protected static string $resource = VolunteerShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
