<?php

namespace App\Filament\Resources\RoutesResource\Pages;

use App\Filament\Resources\RoutesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoutes extends EditRecord
{
    protected static string $resource = RoutesResource::class;

    protected static ?string $title = 'Edit Route';
    protected ?string $subheading = 'A Route defines rules to match client requests, and is associated with a Gateway Service.';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
