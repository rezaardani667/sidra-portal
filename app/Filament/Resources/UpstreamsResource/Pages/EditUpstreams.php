<?php

namespace App\Filament\Resources\UpstreamsResource\Pages;

use App\Filament\Resources\UpstreamsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUpstreams extends EditRecord
{
    protected static string $resource = UpstreamsResource::class;
    protected static ?string $title = 'New Upstream';
    protected ?string $subheading = 'The upstream object represents a virtual hostname and can be used to load balance incoming requests over multiple services (targets).';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
