<?php

namespace App\Http\Controllers;

use App\Models\GatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GatewayServiceController extends Controller
{
    public function register(Request $request)
    {
        $id = $request->input('id');

        $existingRecord = GatewayService::where('id', $id)->first();

        if (!$existingRecord) {
            return response()->json([
                'Message' => 'ID Not Found'
            ], 400);
        }

        $keys = $this->generateKeyPair();

        $existingRecord->public_key = $keys['public_key'];
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
