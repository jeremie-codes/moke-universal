<?php

namespace App\Filament\Resources\Temoignages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TemoignageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Temoignage')
                    ->schema([
                        TextInput::make('nom')
                            ->required(),
                        TextInput::make('role')
                            ->required()
                            ->default('Client'),
                        Textarea::make('content')
                            ->label('Commentaire')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(['md' => 2]),
            ])->columns(1);
    }
}
