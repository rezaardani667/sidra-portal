<?php

namespace App\Filament\Resources\PluginsResource\Pages;

use App\Filament\Resources\PluginsResource;
use App\Models\GatewayService;
use App\Models\Plugin;
use App\Models\PluginRoute;
use App\Models\PluginServiceRoute;
use App\Models\Route;
use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePlugins extends CreateRecord
{
    protected static string $resource = PluginsResource::class;
    protected static ?string $title = 'Select a Plugin';
    protected ?string $subheading = 'Choose a plugin from our catalog to install for your organization.';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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

    protected function handleRecordCreation(array $data): Model
    {
        $typePluginId = $data['type_plugin'];

        $pluginType = DB::table('plugin_types')->find($typePluginId);

        $configs = [];
        if ($pluginType && $pluginType->config) {
            $configKeys = explode(',', $pluginType->config);
            foreach ($configKeys as $key) {
                if (isset($data[$key])) {
                    $configs[$key] = $data[$key];
                }
            }
        }

        $data['config'] = json_encode($configs);

        $plugin = parent::handleRecordCreation($data);

        if ($data['apply_to'] === 'service_routes') {
            $this->createServiceRoutes($plugin, $data);
        }

        return $plugin;
    }

    protected function createServiceRoutes(Plugin $plugin, array $data): void
    {
        if ($data['gatewayService'] == -1 && $data['routes'] == -1) {
            $services = GatewayService::all();
            foreach ($services as $service) {
                $routes = Route::where('gateway_id', $service->id)->get();
                foreach ($routes as $route) {
                    PluginServiceRoute::create([
                        'plugins_id' => $plugin->id,
                        'gateway_id' => $service->id,
                        'routes_id' => $route->id,
                    ]);
                }
            }
        } elseif ($data['routes'] == -1) {
            $routes = Route::where('gateway_id', $data['gatewayService'])->get();
            foreach ($routes as $route) {
                PluginServiceRoute::create([
                    'plugins_id' => $plugin->id,
                    'routes_id' => $route->id,
                    'gateway_id' => $data['gatewayService'],
                ]);
            }
        } else {
            PluginServiceRoute::create([
                'plugins_id' => $plugin->id,
                'routes_id' => $data['routes'],
                'gateway_id' => $data['gatewayService'],
            ]);
        }
    }

    protected function afterCreate(): void
    {
        PluginsResource::setAppliedTo($this->getRecord());
    }
}
