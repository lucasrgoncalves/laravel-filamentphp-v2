<?php

namespace App\Filament\Widgets;

use App\Models\UserOrder;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Últimos pedidos realizados';

    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        return UserOrder::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->label('ID'),
            TextColumn::make('user.name')->label('Nome'),
            TextColumn::make('items_count')->label('Itens Pedido'),
            TextColumn::make('orderTotal')->label('Total')->money('BRL'),
            TextColumn::make('created_at')->label('Data Pedido')->date('d/m/Y H:i:s'),
        ];
    }
}
