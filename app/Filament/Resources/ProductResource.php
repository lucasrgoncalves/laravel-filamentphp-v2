<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\CategoriesRelationManager;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nome')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        $state = Str::slug($state); //Sobrescrevendo o state para preencher na linha de baixo automaticamente o slug
                        $set('slug', $state);
                    }),
                TextInput::make('description')->label('Descrição'),
                TextInput::make('price')->label('Preço'),
                TextInput::make('amount')->label('Quantidade'),
                TextInput::make('slug')->label('Slug')->disabled(),
                FileUpload::make('photo')->directory('products')->image(),
                // Select::make('categories')->relationship('categories', 'name')->multiple()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                ImageColumn::make('photo')->circular(),
                TextColumn::make('name')->label('Nome')->sortable()->searchable(), //Adiciona ordenação e busca por filtro na coluna nome
                TextColumn::make('price')->label('Preço')->money('BRL')->sortable(),
                TextColumn::make('amount')->label('Quantidade'),
                TextColumn::make('created_at')->label('Criado em')->date('d/m/Y h:i:s'),
            ])
            ->filters([
                Filter::make('amount')
                    ->label('Quantidade maior que 10')
                    ->toggle()
                    ->query(fn (Builder $query) => $query->where('amount', '>', 10)), //Adicionando filtro retornar apenas quantidade de produto > 10
                    // ->default(), Utilizar esse filtro como padrão quando listagem for carregada
                Filter::make('amount_mq')
                    ->label('Quantidade menor que 5')
                    ->toggle()
                    ->query(fn (Builder $query) => $query->where('amount', '<', 5))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'DESC');//Ordenação padrão caso queira
    }
    
    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}
