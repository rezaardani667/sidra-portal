<x-filament-panels::page
    @class([ 'fi-resource-create-record-page' , 'fi-resource-' . str_replace('/', '-' , $this->getResource()::getSlug()),
    ])
    >
    <x-filament-panels::form
        id="form"
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="create">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    <x-filament::modal
        :alignment="$action?->getModalAlignment()"
        :autofocus="$action?->isModalAutofocused()"
        :close-button="$action?->hasModalCloseButton()"
        :close-by-clicking-away="$action?->isModalClosedByClickingAway()"
        :close-by-escaping="$action?->isModalClosedByEscaping()"
        display-classes="block"
        :extra-modal-window-attribute-bag="$action?->getExtraModalWindowAttributeBag()"
        :footer-actions="$action?->getVisibleModalFooterActions()"
        :footer-actions-alignment="$action?->getModalFooterActionsAlignment()"
        :heading="$action?->getModalHeading()"
        :icon="$action?->getModalIcon()"
        :icon-color="$action?->getModalIconColor()"
        id='dataplane-install'
        :slide-over="$action?->isModalSlideOver()"
        :sticky-footer="$action?->isModalFooterSticky()"
        :sticky-header="$action?->isModalHeaderSticky()"
        :visible="filled($action)"
        :width="$action?->getModalWidth()"
        :wire:key="$action ? $this->getId() . '.actions.' . $action->getName() . '.modal' : null">

        <x-slot name="description">
            @if ($deployment_models === 'kubernetes')
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Installing Helm Chart</h1>
            <p>Here are the steps to install Sidra via Helm:</p>
            <ol style="list-style-type: decimal; padding-left: 20px; margin-top: 10px; margin-bottom: 20px; font-family: Arial, sans-serif; line-height: 1.6;">
                <li><strong>Step 1: Add the Helm chart repository:</strong></li>
                <pre style="background-color: #f4f4f4; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: 'Courier New', Courier, monospace; color: #333; overflow-x: auto; white-space: nowrap; width: 85%; max-width: 85%; box-sizing: border-box;">
                <code style="display: block; width: 100%; max-width: 100%; box-sizing: border-box;">helm repo add sid https://sidra-api.github.io/sidra/charts</code>
            </pre>

                <li><strong>Step 2: Update the Helm chart repository:</strong></li>
                <pre style="background-color: #f4f4f4; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: 'Courier New', Courier, monospace; color: #333; overflow-x: auto; white-space: nowrap; width: 85%; max-width: 85%; box-sizing: border-box;">
                <code style="display: block; width: 100%; max-width: 100%; box-sizing: border-box;">helm repo update</code>
            </pre>

                <li><strong>Step 3: Install the Helm chart:</strong></li>
                <pre style="background-color: #f4f4f4; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: 'Courier New', Courier, monospace; color: #333; overflow-x: auto; white-space: nowrap; width: 85%; max-width: 85%; box-sizing: border-box;">
                <code style="display: block; width: 100%; max-width: 100%; box-sizing: border-box; margin-right: 10px;">helm upgrade --install sidra sid/sidra --set dataplaneid={{$dataplane_id}}</code>
            </pre>
            </ol>
            @else
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Installing Docker</h1>
            <p>Here are the steps to install Sidra via Docker:</p>
            <ol style="list-style-type: decimal; padding-left: 20px; margin-top: 10px; margin-bottom: 20px; font-family: Arial, sans-serif; line-height: 1.6;">
                <li><strong>Step 1: Pull the Sidra Docker image:</strong></li>
                <pre style="background-color: #f4f4f4; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: 'Courier New', Courier, monospace; color: #333; overflow-x: auto; white-space: nowrap; width: 78%; max-width: 78%; box-sizing: border-box;">
                <code style="display: block; width: 100%; max-width: 100%; box-sizing: border-box;">docker pull ghcr.io/sidra-api/sidra:latest</code>
            </pre>

                <li><strong>Step 2: Run the Sidra container:</strong></li>
                <pre style="background-color: #f4f4f4; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: 'Courier New', Courier, monospace; color: #333; overflow-x: auto; white-space: nowrap; width: 78%; max-width: 78%; box-sizing: border-box;">
                <code style="display: block; width: 100%; max-width: 100%; box-sizing: border-box; margin-right: 10px;">docker run -p 8080:8080 -e dataplaneid={{$dataplane_id}} ghcr.io/sidra-api/sidra:latest</code>
            </pre>
            </ol>
            @endif
        </x-slot>
    </x-filament::modal>

    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>