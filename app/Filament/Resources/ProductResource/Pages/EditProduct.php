<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        //Formata a exibição do preço no input do formulário de edição
        $data['price'] = number_format(($data['price'] / 100), 2, ',', '.');
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //Tratando no momento da edição o salvamento da moeda para conter casas decimais
        $data['price'] = ((float) str_replace(['.', ','], ['', '.'], $data['price'])) * 100;
        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
