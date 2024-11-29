<?php

namespace App\Filament\Resources\UpstreamsResource\Pages;

use App\Filament\Resources\UpstreamsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateUpstreams extends CreateRecord
{
    protected static string $resource = UpstreamsResource::class;
    protected static ?string $title = 'New Upstream';
    protected ?string $subheading = 'The upstream object represents a virtual hostname and can be used to load balance incoming requests over multiple services (targets).';

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
