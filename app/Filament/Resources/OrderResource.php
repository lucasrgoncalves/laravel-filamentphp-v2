<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\UserOrder;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = UserOrder::class;

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'Pedido';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome')
                    ->url(
                        fn(UserOrder $record) => UserResource::getUrl('edit', ['record' => $record->user])
                    ),
                TextColumn::make('items_count')->label('Itens Pedido'),
                TextColumn::make('orderTotal')->label('Total')->money('BRL'),
                TextColumn::make('created_at')->label('Data Pedido')->date('d/m/Y H:i:s'),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_filter')->form(
                    [
                        Forms\Components\DatePicker::make('date_start'),
                        Forms\Components\DatePicker::make('date_end'),
                    ]
                )
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['date_start'],
                                fn($query) => $query->whereDate('created_at', '>=', $data['date_start'])
                            )
                            ->when(
                                $data['date_end'],
                                fn($query) => $query->whereDate('created_at', '<=', $data['date_end'])
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): string
    {
        return self::getModel()::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
