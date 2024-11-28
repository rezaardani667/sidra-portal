<?php

namespace App\Filament\Resources\KeysResource\Pages;

use App\Filament\Resources\KeysResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeys extends ListRecords
{
    protected static string $resource = KeysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
