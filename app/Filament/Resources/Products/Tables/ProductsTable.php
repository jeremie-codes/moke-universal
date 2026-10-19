<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Image')
                    ->collection('images')
                    ->conversion('thumb')
                    ->stacked()
                    ->limit(3)
                    ->circular()
                    ->size(60),

                TextColumn::make('title')
                    ->label('Nom du produit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->badge(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'success' => 'book',
                        'warning' => 'comic',
                        'primary' => 'clothing',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'book' => 'Livre',
                        'comic' => 'Bande dessinée',
                        'clothing' => 'Vêtement',
                        default => $state,
                    }),

                TextColumn::make('price')
                    ->label('Prix')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state == 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),

                IconColumn::make('is_featured')
                    ->label('Mis en avant')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),

            ])
            ->filters([

                SelectFilter::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name'),

                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'book' => 'Livre',
                        'comic' => 'Bande dessinée',
                        'clothing' => 'Vêtement',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Actif'),

                TernaryFilter::make('is_featured')
                    ->label('Mis en avant'),

            ])
            ->recordActions([
                EditAction::make()
                    ->label('Modifier'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
