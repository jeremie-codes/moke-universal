<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informations générales')
                    ->description('Informations principales du site')
                    ->schema([

                        TextInput::make('site_name')
                            ->label('Nom du site')
                            ->required(),

                        TextInput::make('owner_name')
                            ->label('Nom du propriétaire')
                            ->required(),

                        /* TextInput::make('job_title')
                            ->label('Profession'), */

                        Textarea::make('bio')
                            ->label('Biographie')
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Section::make('Contact')
                    ->schema([

                        TextInput::make('email')
                            ->email()
                            ->label('Adresse email'),

                        TextInput::make('phone')
                            ->label('Téléphone'),

                        TextInput::make('address')
                            ->label('Adresse')
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Section::make('Réseaux sociaux')
                    ->schema([

                        TextInput::make('facebook'),

                        TextInput::make('instagram'),

                        TextInput::make('linkedin'),

                        TextInput::make('github'),

                        TextInput::make('youtube'),

                        TextInput::make('whatsapp'),

                    ])
                    ->columns(2),

                /* Section::make('Images')
                    ->schema([

                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logo')
                            ->disk('public')
                            ->image()
                            ->maxSize(5120)
                            ->label('Logo'),

                        SpatieMediaLibraryFileUpload::make('favicon')
                            ->collection('favicon')
                            ->image()
                            ->label('Favicon'),

                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->collection('avatar')
                            ->image()
                            ->label('Photo du propriétaire'),

                        SpatieMediaLibraryFileUpload::make('hero')
                            ->collection('hero')
                            ->image()
                            ->label("Image d'accueil"),

                    ])
                    ->columns(2), */

                Section::make('Paramètres')
                    ->schema([

                        Toggle::make('is_maintenance')
                            ->label('Site en maintenance'),

                    ]),

            ]);
    }
}
