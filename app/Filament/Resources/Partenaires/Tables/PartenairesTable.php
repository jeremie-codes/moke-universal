<?php

namespace App\Filament\Resources\Partenaires\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartenairesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->disk('public')
                    ->label('Photo')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nom complet')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(30)
                    ->suffix('...')
                    ->sortable(),

                TextColumn::make('expertise')
                    ->label('Expertise')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('facebook')
                    ->label('Facebook')
                    ->url(fn ($record): ?string => $record->facebook)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('linkedin')
                    ->label('LinkedIn')
                    ->url(fn ($record): ?string => $record->linkedin)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('instagram')
                    ->label('Instagram')
                    ->url(fn ($record): ?string => $record->instagram)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
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
