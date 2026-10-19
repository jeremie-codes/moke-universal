<?php

namespace App\Filament\Resources\Skills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Compétence')
                    ->searchable()
                    ->sortable(),

                /*TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'backend' => 'Backend',
                        'frontend' => 'Frontend',
                        'mobile' => 'Mobile',
                        'database' => 'Base de données',
                        'design' => 'Design',
                        'devops' => 'DevOps',
                        default => 'Autre',
                    }),*/

                TextColumn::make('level')
                    ->label('Niveau')
                    ->suffix('%')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),

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
