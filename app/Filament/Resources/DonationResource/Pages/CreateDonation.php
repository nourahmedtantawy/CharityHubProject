<?php
namespace App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
class CreateDonation extends CreateRecord
{
    protected static string $resource = DonationResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['idempotency_key'] = Str::uuid()->toString();
        $data['donated_at']      = now();
        return $data;
    }
}