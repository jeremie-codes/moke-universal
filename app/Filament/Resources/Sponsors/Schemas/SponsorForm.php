<?php

namespace App\Filament\Resources\Sponsors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SponsorForm
{
    public static function configureForCreate(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('sponsors')
                    ->label('Sponsors')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo du sponsor')
                            ->disk('public')
                            ->directory('sponsors')
                            ->image()
                            ->maxSize(2048)
                            ->required(),
                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Visible'),
                    ])
                    ->minItems(1)
                    ->maxItems(6)
                    ->columnSpan(1)
                    ->defaultItems(1)
                    ->addActionLabel('Ajouter un autre formulaire'),

            ])->columns(1);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sponsor')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo du sponsor')
                            ->disk('public')
                            ->directory('sponsors')
                            ->image()
                            ->maxSize(2048),
                        Toggle::make('is_active')
                            ->default(true)
                            ->label('visible'),

                    ])
            ]);
    }
}
