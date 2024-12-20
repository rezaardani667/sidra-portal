<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Throwable;

use function Filament\Support\is_app_url;

class EditDataPlaneNodes extends EditRecord
{
    protected static string $resource = DataPlaneNodesResource::class;
    protected static ?string $title = 'Gateway Manager';
    protected static string $view = 'filament.pages.edit-record';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('open-modal', id: 'dataplane-install');
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();
            $this->callHook('beforeValidate');
            $data = $this->form->getState(afterValidate: function () {
            $this->callHook('afterValidate');
            $this->callHook('beforeSave');
            });
            $data = $this->mutateFormDataBeforeSave($data);
            $this->handleRecordUpdate($this->getRecord(), $data);
            $this->callHook('afterSave');
            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
            $this->rollBackDatabaseTransaction() :
            $this->commitDatabaseTransaction();
            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        }
        $this->rememberData();
        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()?->send();
        }
    }

    protected function getViewData(): array
    {
        return [
            'deployment_models' => $this->record->deployment_models ?? 'kubernetes',
            'dataplane_id' => $this->record->id ?? null,
            'action' => $this->modal(),
        ];
    } 
    
    private function modal() : Action
    {
        return Action::make('close')
            ->modalAlignment(Alignment::Left)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalCloseButton(false)
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            ->modalHeading('How to install Sidra')
            ->modalSubmitAction(StaticAction::make('join-group')
                ->label('Close')
                ->button()
                ->openUrlInNewTab(true)
                ->url(fn (): string => $this->record ? DataPlaneNodesResource::getUrl('view', ['record' => $this->record->id]) : '#')
            )
            ->modalCancelAction(false);
    }
}
