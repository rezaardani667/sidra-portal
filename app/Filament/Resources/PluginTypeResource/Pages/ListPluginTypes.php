<?php

namespace App\Filament\Resources\PluginTypeResource\Pages;

use App\Filament\Resources\PluginTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPluginTypes extends ListRecords
{
    protected static string $resource = PluginTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
