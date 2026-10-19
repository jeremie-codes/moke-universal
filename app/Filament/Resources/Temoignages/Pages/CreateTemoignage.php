<?php

namespace App\Filament\Resources\Temoignages\Pages;

use App\Filament\Resources\Temoignages\TemoignageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTemoignage extends CreateRecord
{
    protected static string $resource = TemoignageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
