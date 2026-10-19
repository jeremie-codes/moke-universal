<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informations générales')
                    ->description('Informations principales de la catégorie')
                    ->schema([

                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('slug', Str::slug($state));
                            })->columnSpanFull(),

                        TextInput::make('slug')
                            ->hidden(),

                        Textarea::make('description')
                            ->label('Description')
                            ->default(null)
                            ->columnSpanFull(),

                    ])
                    ->columns(2),


                Section::make('Configuration')
                    ->description('Gestion de la visibilité de la catégorie')
                    ->schema([

                        Toggle::make('is_active')
                            ->label('Catégorie active')
                            ->default(true),

                    ]),

            ]);
    }
}
