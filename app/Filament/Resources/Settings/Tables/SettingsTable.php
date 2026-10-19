<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                /* SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->label('Logo')
                    ->size(50), */

                TextColumn::make('bio')
                    ->label('Biographie')
                    ->limit(50),

                TextColumn::make('site_name')
                    ->label('Nom du site')
                    ->searchable(),

                TextColumn::make('owner_name')
                    ->label('Propriétaire'),

                TextColumn::make('email')
                    ->label('Email'),

                IconColumn::make('is_maintenance')
                    ->label('Maintenance')
                    ->boolean(),

            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
