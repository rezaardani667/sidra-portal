<?php

namespace App\Http\Controllers;

use App\Models\GatewayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GatewayConfigController extends Controller
{
    /**
     * Menampilkan konfigurasi berdasarkan gateway_id
     *
     * @param  string  $gateway_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($gateway_id)
    {
        // Ambil data GatewayService berdasarkan gateway_id
        $gatewayService = GatewayService::with(['routes', 'consumers', 'plugins'])
                                        ->find($gateway_id);

        // Cek apakah GatewayService ditemukan
        if (!$gatewayService) {
            return response()->json([
                'message' => 'Gateway service not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Kembalikan response dengan data yang diminta
        return response()->json([
            'Routes' => $gatewayService->routes,    // Data Routes terkait dengan GatewayService
            'Consumers' => $gatewayService->consumers, // Data Consumers terkait dengan GatewayService
            'Plugins' => $gatewayService->plugins    // Data Plugins terkait dengan GatewayService
        ]);
    }
}
