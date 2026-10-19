<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = OrderResource::class;

    protected static ?string $title = 'Articles commandés';


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('product_id')
                    ->label('Produit')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('quantity')
                    ->label('Quantité')
                    ->numeric()
                    ->default(1)
                    ->required(),

                TextInput::make('price')
                    ->label('Prix unitaire')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('product.title')
                    ->label('Produit')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Quantité'),

                TextColumn::make('price')
                    ->label('Prix unitaire')
                    ->money('USD'),

                TextColumn::make('total')
                    ->label('Total')
                    ->state(fn ($record) => $record->price * $record->quantity)
                    ->money('USD'),

            ])
            ->headerActions([
                CreateAction::make()->label('Ajouter un article'),
            ])
            ->recordActions([
                EditAction::make()->label('Modifier l\'article'),
                DeleteAction::make()->label('Supprimer l\'article'),
            ]);
    }
}
