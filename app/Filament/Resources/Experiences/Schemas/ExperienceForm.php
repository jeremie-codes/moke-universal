<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Expérience professionnelle')
                    ->description('Informations sur une expérience')
                    ->schema([

                        TextInput::make('company')
                            ->label('Entreprise')
                            ->required(),

                        TextInput::make('position')
                            ->label('Poste occupé')
                            ->required(),

                        /*TextInput::make('location')
                            ->label('Lieu'),*/

                        DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required(),

                        DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->visible(fn ($get) => ! $get('is_current')),

                        Toggle::make('is_current')
                            ->label('Poste actuel')
                            ->live(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(6)
                            ->columnSpanFull(),

                        /*TextInput::make('position')
                            ->label("Ordre d'affichage")
                            ->numeric()
                            ->default(1),
                            */

                        Toggle::make('is_active')
                            ->label('Visible')
                            ->default(true),

                    ])
                    ->columns(2),

            ]);
    }
}
