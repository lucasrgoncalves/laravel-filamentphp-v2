<?php

namespace App\Filament\Widgets;

use App\Models\UserOrder;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends LineChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Vendas por mês';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Chart dinâmico
        $data = Trend::model(UserOrder::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Vendas últimos 30 dias',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];


        // Chart estático
        // return [
        //     'datasets' => [
        //         [
        //             'label' => 'Vendas últimos 30 dias',
        //             'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
        //             'backgroundColor' => '#000000',
        //             'borderColor' => '#000000',
        //             'borderWidth' => 2,
        //         ],
        //         [
        //             'label' => 'Vendas últimos 30 dias',
        //             'data' => [0, 50, 70, 22, 21, 38, 40, 15, 19, 75, 07, 33],
        //             'backgroundColor' => '#FF0000',
        //             'borderColor' => '#FF0000',
        //             'borderWidth' => 2,
        //         ],
        //     ],
        //     'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        // ];
    }
}
