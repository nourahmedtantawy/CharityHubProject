<?php

namespace App\Filament\Resources\VolunteerShiftResource\Pages;

use App\Filament\Resources\VolunteerShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVolunteerShift extends EditRecord
{
    protected static string $resource = VolunteerShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
