<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\UserOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Total de Pedidos', UserOrder::count())
                ->icon('heroicon-o-shopping-cart'),
            Card::make('Total Ganho (30 dias)', $this->getThirtyDaysOrders())
                ->description('Total Vendido Geral')
                ->chart([10, 50, 5])
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }

    protected function getThirtyDaysOrders()
    {
        $result = OrderItem::where('created_at', '>', now()->subdays(30))->sum('order_value');
        return 'R$ ' . number_format($result / 100, 2, ',', '.');
    }
}
