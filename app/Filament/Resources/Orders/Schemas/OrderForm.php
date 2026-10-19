<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Client')
                    ->description('Informations du client')
                    ->schema([

                        TextInput::make('fullname')
                            ->label('Nom complet')
                            ->required(),

                        TextInput::make('email')
                            ->label('Adresse email')
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->required(),

                    ])
                    ->columns(2),


                Section::make('Adresse du client (Livraison)')
                    ->schema([

                        Textarea::make('address')
                            ->label('Adresse')
                            ->required()
                            ->columnSpanFull(),

                    ]),


                Section::make('Commande')
                    ->description('Informations financières et statut')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Sous-total')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled(fn(?Order $record) => $record === null)
                            ->required(fn (?Order $record) => $record !== null),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled(fn(?Order $record) => $record === null)
                            ->required(fn (?Order $record) => $record !== null),

                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending' => 'En attente',
                                'paid' => 'Payée',
                                'cancelled' => 'Annulée',
                                'failed' => 'Échec',
                            ])
                            ->default('pending')
                            ->disabled(fn(?Order $record) => $record === null)
                            ->dehydrated()
                            ->required(),

                    ])
                    ->columns(3),

            ]);
    }
}
