<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Paiement')
                    ->description('Informations principales du paiement')
                    ->schema([
                        Select::make('order_id')
                            ->label('Commande')
                            ->relationship('order', 'reference')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('method')
                            ->label('Méthode de paiement')
                            ->options([
                                'mobile' => 'Mobile Money',
                                'card' => 'Carte bancaire',
                            ])
                            ->reactive()
                            ->required(),

                        Select::make('provider')
                            ->label('Fournisseur')
                            ->options([
                                'visa' => 'Visa',
                                'mastercard' => 'Mastercard',
                                'mpesa' => 'M-Pesa',
                                'orange_money' => 'Orange Money',
                                'airtel_money' => 'Airtel Money',
                                'afrimoney' => 'Afrimoney',
                                'other' => 'Autre',
                            ])
                            ->searchable()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Numéro de téléphone')
                            ->tel()
                            ->required(fn ($get) => $get('method') === 'mobile'),

                        TextInput::make('amount')
                            ->label('Montant du paiement')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Select::make('currency')
                            ->label('Devise')
                            ->options([
                                'USD' => 'USD',
                                'CDF' => 'CDF',
                                ])
                            ->default('USD')
                            ->required()
                            ->dehydrated(),

                    ])
                    ->columns(2),

                Section::make('Informations techniques')
                    ->description('Données générées par le système de paiement')
                    ->schema([
                        TextInput::make('reference')
                            ->label('Référence')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('transaction_id')
                            ->label('ID Transaction')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('response')
                            ->label('Réponse FlexPaie')
                            ->hidden()
                            ->columnSpanFull(),

                        DateTimePicker::make('paid_at')
                            ->label('Payé le')
                            ->hidden()
                            ->dehydrated(false),

                    ])
                    ->columns(2),

                Select::make('status')
                    ->hidden()
                    ->default('success')
                    ->dehydrated(),

            ]);
    }
}
