<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //Tratando no momento da criação o salvamento da moeda para conter casas decimais
        $data['price'] = ((float) str_replace(['.', ','], ['', '.'], $data['price'])) * 100;
        return $data;
    }
}
