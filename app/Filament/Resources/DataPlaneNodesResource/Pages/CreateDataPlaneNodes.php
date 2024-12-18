<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateDataPlaneNodes extends CreateRecord
{
    protected static string $resource = DataPlaneNodesResource::class;
    protected static ?string $title = 'Create a Gateway';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
            Action::make('Config')
                ->label('View Configuration')
                ->link(),
        ];
    }
}
