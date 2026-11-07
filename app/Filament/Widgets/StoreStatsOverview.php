<?php

namespace App\Filament\Widgets;

use App\Models\newsletter;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $paidRevenue = (float) Order::query()->where('status', 'paid')->sum('total');

        return [
            Stat::make('Produits', Product::count()),
            Stat::make('Commandes', Order::count())
                ->description(Order::where('status', 'pending')->count().' en attente')
                ->color('warning'),
            Stat::make('Commandes payées', Order::where('status', 'paid')->count())
                ->color('success'),
            Stat::make('Chiffre d\'affaires', number_format($paidRevenue, 2, ',', ' ').' $')
                ->description('Commandes payées')
                ->color('success'),
            Stat::make('Abonnés newsletter', newsletter::count()),
            Stat::make('Clients', User::count()),
        ];
    }
}
