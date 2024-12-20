<?php

namespace App\Filament\Resources\GatewayServicesResource\Pages;

use App\Filament\Resources\GatewayServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGatewayServices extends EditRecord
{
    protected static string $resource = GatewayServicesResource::class;
    protected static ?string $title = 'New Gateway Service';
    protected ?string $subheading = 'Service entities are abstractions of each of your own upstream services.';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record->id]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill([
            ...$record->attributesToArray(),
            ...$extraData,
        ]);

        $data['traffic'] = !empty($data['upstream_url']) ? 'full_url' : 'host';

        $this->form->fill($data);

        $this->callHook('afterFill');
    }
}
