<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Image')
                    ->collection('images')
                    ->conversion('thumb')
                    ->size(60),

                TextColumn::make('title')
                    ->label('Projet')
                    ->searchable()
                    ->sortable(),


                IconColumn::make('is_published')
                    ->label('Visible')
                    ->boolean(),

                TextColumn::make('published_at')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options([
                        'web' => 'Site Web',
                        'mobile' => 'Application mobile',
                        'desktop' => 'Desktop',
                        'design' => 'UI/UX',
                        'branding' => 'Branding',
                        'other' => 'Autre',
                    ]),

                TernaryFilter::make('is_featured')
                    ->label('Mis en avant'),

                TernaryFilter::make('is_active')
                    ->label('Visible'),

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
