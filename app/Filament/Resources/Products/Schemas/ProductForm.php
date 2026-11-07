<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Informations')
                    ->schema([
                        Select::make('category_id')
                            ->label('Catégorie')
                            ->options(Category::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        /*TextInput::make('slug')
                            ->required(),*/
                        Select::make('type')
                            ->options([
                                'book' => 'Livre',
                                'comic' => 'Bande dessinée',
                                'clothing' => 'Vêtement',
                            ])
                            ->required(),
                        RichEditor::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Images du produit')
                    ->description('Ajoutez les images du produit')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Images')
                            ->collection('images')
                            ->disk('public')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->maxSize(5120),
                    ]),

                Section::make('Tarification')
                    ->description('Le stock est géré par option. Le prix du produit s’applique à toutes ses variantes et options.')
                    ->schema([
                        TextInput::make('old_price')
                            ->label('Ancien Prix')
                            ->placeholder('ce prix sera barré')
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('price')
                            ->label('Prix')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('stock')
                            ->numeric()
                            ->required(),
                        /*TextInput::make('sku')
                            ->required(),*/
                        /*TextInput::make('weight')
                            ->numeric(),*/
                    ])
                    ->columns(3),

                Section::make('Modes de réception')
                    ->schema([
                        CheckboxList::make('deliveryTypes')
                            ->label('Modes autorisés pour ce produit')
                            ->relationship(titleAttribute: 'label')
                            ->columns(3),
                    ]),

                Section::make('Livre')
                    ->schema([
                        /*TextInput::make('author')
                            ->label('Auteur'),
                        TextInput::make('publisher')
                            ->label('Editeur'),*/
                        DatePicker::make('release_date')
                            ->label('Date de sortie'),
                    ])
                    ->columns(3),

                Section::make('Options')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Produit mis en avant'),
                        Toggle::make('is_active')
                            ->label('Produit actif')
                            ->default(true),

                    ])
                    ->columns(2),

                Section::make('Variantes et options')
                    ->description('Ajoutez des options directement au produit ou des variantes (par exemple les couleurs) avec leurs propres options (par exemple les tailles).')
                    ->schema([
                        Repeater::make('options')
                            ->relationship()
                            ->label('Options du produit')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nom (ex. S, M, L, EN, FR, PR)')
                                    ->required(),
                                TextInput::make('stock')
                                    ->label('Stock')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Ajouter une option'),
                        Repeater::make('variants')
                            ->relationship()
                            ->label('Variantes (ex. couleurs)')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nom (ex. Rouge, Bleu)')
                                    ->required(),
                                Repeater::make('options')
                                    ->relationship()
                                    ->label('Options de cette variante (ex. tailles)')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nom (ex. S, M, L)')
                                            ->required(),
                                        TextInput::make('stock')
                                            ->label('Stock')
                                            ->numeric()
                                            ->integer()
                                            ->minValue(0)
                                            ->default(0)
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Ajouter une option'),
                            ])
                            ->addActionLabel('Ajouter une variante'),
                    ]),


            ]);
    }
}
