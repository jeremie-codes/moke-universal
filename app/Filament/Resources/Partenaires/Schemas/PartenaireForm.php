<?php

namespace App\Filament\Resources\Partenaires\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartenaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations')
                    ->description('Informations principales du collaborateur')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom complet')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('expertise')
                            ->label('Expertise') 
                            ->placeholder('Ex. Designer graphique')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->placeholder('Ecrivez un détail sur lui (optionnel)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('Réseaux sociaux')
                    ->description('Liens publics du collaborateur')
                    ->schema([
                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('linkedin')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Photo')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Image profil')
                            ->disk('public')
                            ->directory('partenaires')
                            ->image()
                            ->maxSize(5120)
                    ]),
            ]);
    }
}
