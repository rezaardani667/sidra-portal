<x-filament-panels::page
    @class([
        'fi-resource-create-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <x-filament-panels::form
        id="form"
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="create"
    >
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
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
        id='register-action'
        :slide-over="$action?->isModalSlideOver()"
        :sticky-footer="$action?->isModalFooterSticky()"
        :sticky-header="$action?->isModalHeaderSticky()"
        :visible="filled($action)"
        :width="$action?->getModalWidth()"
        :wire:key="$action ? $this->getId() . '.actions.' . $action->getName() . '.modal' : null"

    >
        <x-slot name="description">
            <h1>Installing Helm Chart</h1>
            <p>Here are the steps to install a Sidra via Helm chart:</p>
            <ol>
                <li>Step 1: Add the Helm chart repository:</li>
                <pre><code>helm repo add sid https://sidra-api.github.io/sidra/charts</code></pre>
                <li>Step 2: Update the Helm chart repository:</li>
                <pre><code>helm repo update</code></pre>
                <li>Step 3: Install the Helm chart:</li>
                <pre><code>helm upgrade --install sidra sid/sidra --set dataplaneid=UUID</code></pre>
            </ol>

        </x-slot>
    </x-filament::modal>

    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>
