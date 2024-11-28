<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataPlaneNodes extends ListRecords
{
    protected static string $resource = DataPlaneNodesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
