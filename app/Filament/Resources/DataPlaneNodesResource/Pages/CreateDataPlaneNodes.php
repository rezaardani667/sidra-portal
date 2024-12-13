<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDataPlaneNodes extends CreateRecord
{
    protected static string $resource = DataPlaneNodesResource::class;
    protected static ?string $title = 'Create a Gateway';
}
