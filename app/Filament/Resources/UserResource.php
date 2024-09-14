<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?int $navigationSort = 1;

    // Habilitando busca global pela coluna name da tabela users
    // Abaixo foi adicionada a função getGloballySearchableAttributes para buscar por mais de um campo
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nome')->required(),
                TextInput::make('email')->label('Email')->email()->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->rule(Password::default()),
                TextInput::make('password_confirmation')
                    ->password()
                    ->same('password')
                    ->rule(Password::default()),
                Select::make('role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->label('Nome'),
                TextColumn::make('email')->label('E-mail'),
                TextColumn::make('created_at')->label('Criado em')->date('d/m/Y h:i:s'),
                TextColumn::make('updated_at')->label('Atualizado em')->date('d/m/Y h:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('change_password')
                    ->label('Alterar senha')
                    ->form([
                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->required()
                            ->rule(Password::default()),
                        TextInput::make('password_confirmation')
                            ->label('Confirme a senha')
                            ->password()
                            ->same('password')
                            ->rule(Password::default())
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'password' => bcrypt($data['password'])
                        ]);
                        Filament::notify('success', 'Senha atualizada com sucesso!');
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),

            //Caso deixar comentado esse create, ao clicar em "Criar usuário" será exibido um modal e não mais uma nova página com o form de criação
            // 'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    //Sobrescreve o método canCreate 'escondendo' o botão para criação de um novo usuário caso o return seja false
    // public static function canCreate(): bool
    // {
    //     return true;
    // }

    // Efetua a contagem de registros e exibe em forma de badge em frente ao nome do item do menu lateral
    protected static function getNavigationBadge(): string
    {
        return self::getModel()::count();
    }

    // Efetua busca global na tabela users utilizando as colunas name e email
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
