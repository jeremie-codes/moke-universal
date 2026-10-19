<?php

namespace App\Filament\Resources\Sponsors\Pages;

use App\Filament\Resources\Sponsors\SponsorResource;
use App\Filament\Resources\Sponsors\Schemas\SponsorForm;
use App\Models\Sponsor;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateSponsor extends CreateRecord
{
    protected static string $resource = SponsorResource::class;

    public function form(Schema $schema): Schema
    {
        return SponsorForm::configureForCreate($schema);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Sponsor {
            $sponsors = array_values($data['sponsors']);
            $record = Sponsor::create(array_shift($sponsors));

            foreach ($sponsors as $sponsor) {
                Sponsor::create($sponsor);
            }

            return $record;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
