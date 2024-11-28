<?php

namespace App\Filament\Resources\PluginsResource\Pages;

use App\Filament\Resources\PluginsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
