<?php

namespace App\Filament\Resources\Experiences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExperiencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('company')
                    ->label('Entreprise')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('position')
                    ->label('Poste')
                    ->searchable(),

                /*TextColumn::make('location')
                    ->label('Lieu')
                    ->toggleable(),*/

                TextColumn::make('start_date')
                    ->label('Début')
                    ->date('M Y'),

                TextColumn::make('end_date')
                    ->label('Fin')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->is_current ? 'Aujourd’hui' : optional($state)->format('M Y');
                    }),

                IconColumn::make('is_current')
                    ->label('Actuel')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),

            ])

            ->defaultSort('position')

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
