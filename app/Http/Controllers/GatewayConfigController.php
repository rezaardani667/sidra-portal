<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\GatewayService;
use App\Models\Plugin;
use App\Models\PluginServiceRoute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GatewayConfigController extends Controller
{
    /**
     * Get the configuration for a given gateway.
     *
     * @param int $gateway_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function config($gateway_id)
    {
        $gatewayService = GatewayService::find($gateway_id);

        if (!$gatewayService) {
            return response()->json(['error' => 'Gateway not found'], Response::HTTP_NOT_FOUND);
        }

        $plugins = Plugin::whereIn('id', function ($query) use ($gateway_id) {
            $query->select('plugins_id')
            ->from('plugin_service_route')
            ->where('gateway_id', $gateway_id);
        })->get();

        $id = $plugins->pluck('id');

        $consumers = Consumer::whereIn('id', function ($query) use ($id) {
            $query->select('consumers_id')
            ->from('plugins')
            ->whereIn('id', $id);
        })->get();

        $routes = $gatewayService->routes->map(function ($route) {

            $plugins = Plugin::whereIn('id', function ($query) use ($route) {
                $query->select('plugins_id')
                    ->from('plugin_service_route')
                    ->where('routes_id', $route->id);
            })->pluck('type_plugin');

            return [
                'id' => $route->id,
                'gateway_id' => $route->gateway_id,
                'tags' => $route->tags,
                'methods' => $route->methods,
                'upstream_host' => parse_url($route->upstream_url, PHP_URL_HOST),
                'upstream_port' => parse_url($route->upstream_url, PHP_URL_PORT),
                'path' => $route->paths,
                'pathType' => $route->path_type,
                'expression' => $route->expression,
                'created_at' => $route->created_at,
                'updated_at' => $route->updated_at,
                'plugins' => $plugins
            ];
        });

        return response()->json([
            'GatewayService' => [
                'host' => $gatewayService->domain
            ],
            'Routes' => $routes,
            'Plugins' => $plugins,
            'Consumers' => $consumers
        ], Response::HTTP_OK);
    }
}
