<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informations')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state))
                            ),
                        TextInput::make('slug')
                            ->hidden(),

                        Select::make('categorie')
                            ->required()
                            ->options([
                                'painture' => 'Painture',
                                'tableau' => 'Tableau',
                                'graphic' => 'Graphic',
                                'autres' => 'Autres',
                            ]),

                        RichEditor::make('content')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Galerie')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('images')
                            ->multiple()
                            ->disk('public')
                            ->reorderable()
                            ->image()
                            ->label('Images'),
                    ]),
                Section::make('Options')
                    ->schema([
                        DatePicker::make('published_at')
                            ->label('Date de réalisation'),
                        Toggle::make('is_published')
                            ->label('Visible')
                            ->default(true),
                    ]),

            ]);
    }
}
