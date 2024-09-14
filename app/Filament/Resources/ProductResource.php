<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\CategoriesRelationManager;
use App\Models\Product;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Produtos';

    protected static ?string $modelLabel = 'Produto';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        // Exibindo em formato de seção
        // return $form
        //     ->columns(1)
        //     ->schema([
        //         Section::make('Dados do Produto')
        //             ->description('Cadastre os dados iniciais do produto')
        //             ->schema([
        //                 TextInput::make('name')->label('Nome')
        //                     ->reactive()
        //                     ->afterStateUpdated(function ($state, $set) {
        //                         $state = Str::slug($state);
        //                         $set('slug', $state);
        //                     }),
        //                 TextInput::make('description')->label('Descrição'),
        //                 TextInput::make('price')->label('Preço'),
        //                 TextInput::make('amount')->label('Quantidade'),
        //             ]),
        //         Section::make('Imagem do Produto')
        //             ->description('Cadastre categoria, imagem e slug do produto')
        //             ->schema([
        //                 Select::make('categories')->relationship('categories', 'name')->preload()->multiple(),
        //                 TextInput::make('slug')->label('Slug')->disabled(),
        //                 FileUpload::make('photo')->directory('products')->image()->columnSpanFull(),
        //             ])
        //     ]);


        // Exibindo em formato de wizard
        return $form
            ->columns(1)
            ->schema([
                Fieldset::make('')->schema([
                    Wizard::make()->schema([
                        Wizard\Step::make('Dados do Produto')->schema([
                            TextInput::make('name')->label('Nome')
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set) {
                                    $state = Str::slug($state);
                                    $set('slug', $state);
                                })
                                ->required(),
                            TextInput::make('description')->label('Descrição')->required(),
                            TextInput::make('price')->label('Preço')->required(),
                            TextInput::make('amount')->label('Quantidade')->required(),
                        ]),
                        Wizard\Step::make('Imagem do Produto')->schema([
                            Select::make('categories')->relationship('categories', 'name')->label('Categorias')->preload()->multiple(),
                            TextInput::make('slug')->label('Slug')->disabled()->required(),
                            FileUpload::make('photo')->label('Imagem do Produto')->required()->directory('products')->image()->columnSpanFull(),
                        ])
                    ])
                        ->columnSpanFull()
                ])
            ]);

        // Exibindo em formato de tabs
        // return $form
        //     ->columns(1)
        //     ->schema([
        //         Tabs::make('Tabs')->tabs([
        //             Tab::make('Dados do Produto')->schema([
        //                 TextInput::make('name')->label('Nome')
        //                     ->reactive()
        //                     ->afterStateUpdated(function ($state, $set) {
        //                         $state = Str::slug($state);
        //                         $set('slug', $state);
        //                     }),
        //                 TextInput::make('description')->label('Descrição'),
        //                 TextInput::make('price')->label('Preço'),
        //                 TextInput::make('amount')->label('Quantidade'),
        //             ]),
        //             Tab::make('Imagem do Produto')->schema([
        //                 Select::make('categories')->relationship('categories', 'name')->multiple(),
        //                 TextInput::make('slug')->label('Slug')->disabled(),
        //                 FileUpload::make('photo')->directory('products')->image()->columnSpanFull(),
        //             ])
        //         ])
        //     ]);



        // Exibindo em formato de Fieldset
        // return $form
        //     ->schema([
        //         Fieldset::make('Dados do Produto')->schema([
        //             TextInput::make('name')->label('Nome')
        //                 ->reactive()
        //                 ->afterStateUpdated(function ($state, $set) {
        //                     $state = Str::slug($state); //Sobrescrevendo o state para preencher na linha de baixo automaticamente o slug
        //                     $set('slug', $state);
        //                 }),
        //             TextInput::make('description')->label('Descrição'),
        //             TextInput::make('price')->label('Preço'),
        //             TextInput::make('amount')->label('Quantidade'),
        //         ]),
        //         Fieldset::make('Imagem do Produto')->schema([
        //             Select::make('categories')->relationship('categories', 'name')->multiple(),
        //             TextInput::make('slug')->label('Slug')->disabled(),
        //             FileUpload::make('photo')->directory('products')->image()->columnSpanFull(),
        //         ])
        //     ]);
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
                    ->query(fn(Builder $query) => $query->where('amount', '>', 10)), //Adicionando filtro retornar apenas quantidade de produto > 10
                // ->default(), Utilizar esse filtro como padrão quando listagem for carregada
                Filter::make('amount_mq')
                    ->label('Quantidade menor que 5')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('amount', '<', 5))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'DESC'); //Ordenação padrão caso queira
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

    protected static function getNavigationBadge(): string
    {
        return self::getModel()::count();
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
