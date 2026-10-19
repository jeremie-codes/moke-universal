<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Compétence')
                    ->description('Informations sur une compétence')
                    ->schema([

                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(100),

                        /*Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'backend' => 'Backend',
                                'frontend' => 'Frontend',
                                'mobile' => 'Mobile',
                                'database' => 'Base de données',
                                'design' => 'Design',
                                'devops' => 'DevOps',
                                'autre' => 'Autre',
                            ])
                            ->searchable()
                            ->required(),*/

                        Slider::make('level')
                            ->label('Niveau')
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(5)
                            ->default(80)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Visible')
                            ->default(true),

                    ])
                    ->columns(2),

            ]);
    }
}
