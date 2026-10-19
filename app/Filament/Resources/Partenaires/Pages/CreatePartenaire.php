<?php

namespace App\Filament\Resources\Partenaires\Pages;

use App\Filament\Resources\Partenaires\PartenaireResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartenaire extends CreateRecord
{
    protected static string $resource = PartenaireResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
