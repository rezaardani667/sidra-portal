<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataPlaneNodes extends EditRecord
{
    protected static string $resource = DataPlaneNodesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
