<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Throwable;

class CreateDataPlaneNodes extends CreateRecord
{
    protected static string $resource = DataPlaneNodesResource::class;
    protected static ?string $title = 'Create a Gateway';
}
