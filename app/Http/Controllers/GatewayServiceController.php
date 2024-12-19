<?php

namespace App\Http\Controllers;

use App\Models\DataPlaneNodes;
use App\Models\GatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GatewayServiceController extends Controller
{
    public function getGatewayServices($data_plane_id)
    {
        $gatewayServices = GatewayService::where('data_plane_id', $data_plane_id)->pluck('id');

        if ($gatewayServices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No gateway services found for the given data_plane_id.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'gateway_service_id' => $gatewayServices,
        ]);
    }

    public function register(Request $request)
    {
        $id = $request->input('id');

        $existingRecord = DataPlaneNodes::where('id', $id)->first();

        if (!$existingRecord) {
            return response()->json([
                'Message' => 'ID Not Found'
            ], 400);
        }

        if($existingRecord->public_key) {
            return response()->json([
                'PrivateKey' => $existingRecord->private_key
            ]);
        }

        $keys = $this->generateKeyPair();

        $existingRecord->private_key= $keys['private_key'];
        $existingRecord->public_key = $keys['public_key'];
        $existingRecord->status = 'active';
        $existingRecord->save();

        return response()->json([
            'PrivateKey' => $keys['private_key']
        ]);
    }

    private function generateKeyPair()
    {
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($privateKey, $privateKeyOutput);

        $base64PrivateKey = base64_encode($privateKeyOutput);

        $details = openssl_pkey_get_details($privateKey);
        $publicKey = $details['key'];

        return [
            'private_key' => $base64PrivateKey,
            'public_key' => $publicKey
        ];
    }
}
