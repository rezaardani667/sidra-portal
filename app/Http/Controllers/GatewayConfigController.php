<?php

namespace App\Http\Controllers;

use App\Models\GatewayService;
use App\Models\Plugin;
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
        $gatewayService = $this->getGatewayService($gateway_id);

        if (!$gatewayService) {
            return $this->gatewayNotFoundResponse();
        }

        $routesWithPlugins = $this->getRoutesWithPlugins($gatewayService);

        return $this->successResponse($routesWithPlugins, $gatewayService);
    }

    /**
     * Retrieve the gateway service along with related routes, consumers, and plugins.
     *
     * @param int $gateway_id
     * @return \App\Models\GatewayService|null
     */
    private function getGatewayService($gateway_id)
    {
        return GatewayService::with(['routes', 'consumers', 'plugins'])->find($gateway_id);
    }

    /**
     * Return the 'gateway not found' response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function gatewayNotFoundResponse()
    {
        return response()->json([
            'message' => 'Gateway service not found'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Prepare the routes along with their plugins.
     *
     * @param \App\Models\GatewayService $gatewayService
     * @return array
     */
    private function getRoutesWithPlugins($gatewayService)
    {
        $plugins = Plugin::whereIn('routes_id', $gatewayService->routes->pluck('id'))->get();

        return $gatewayService->routes->map(function ($route) use ($plugins) {

            $routePlugins = $plugins->where('routes_id', $route->id)->pluck('type_plugin')->values();

            return [
                'id' => $route->id,
                'name' => $route->name,
                'tags' => $route->tags,
                'protocol' => $route->protocol,
                'host' => $route->host,
                'methods' => $route->methods,
                'path' => $route->path,
                'expression' => $route->expression,
                'created_at' => $route->created_at,
                'updated_at' => $route->updated_at,
                'plugins' => $routePlugins
            ];
        })->toArray();
    }

    /**
     * Return the success response with the routes, consumers, and plugins.
     *
     * @param array $routesWithPlugins
     * @param \App\Models\GatewayService $gatewayService
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse($routesWithPlugins, $gatewayService)
    {
        return response()->json([
            'Routes' => $routesWithPlugins,
            'Consumers' => $gatewayService->consumers,
            'Plugins' => $this->transformPlugins($gatewayService->plugins)
        ]);
    }

    /**
     * Transform the plugins to exclude unnecessary fields.
     *
     * @param \Illuminate\Database\Eloquent\Collection $plugins
     * @return array
     */
    private function transformPlugins($plugins)
    {
        return $plugins->map(function ($plugin) {
            
            return [
                'id' => $plugin->id,
                'name' => $plugin->name,
                'type_plugin' => $plugin->type_plugin,
                'enabled' => $plugin->enabled,
                'config' => $plugin->config,
                'applied_to' => $plugin->applied_to,
                'protocols' => $plugin->protocols,
                'ordering' => $plugin->ordering,
                'tags' => $plugin->tags,
                'created_at' => $plugin->created_at,
                'updated_at' => $plugin->updated_at
            ];
        })->toArray();
    }
}