<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

class EthereumController extends Controller
{
    protected $rpcUrl;

    public function __construct()
    {
        $this->rpcUrl = env('ETHEREUM_NODE_URL', 'https://rpc.sophelia-testnet.io/v1/YOUR_PROJECT_ID');
    }

    public function getValue()
    {
        $contractAddress = env('ETHEREUM_CONTRACT_ADDRESS');
        $functionSelector = '0x20965255'; // keccak256('getValue()') => lấy 4 byte đầu

        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'eth_call',
            'params' => [[
                'to' => $contractAddress,
                'data' => $functionSelector
            ], 'latest'],
            'id' => 1,
        ];

        $response = Http::post($this->rpcUrl, $payload);

        $data = $response->json();

        if (isset($data['result'])) {
            $valueHex = $data['result'];
            $value = hexdec($valueHex);
            return response()->json(['value' => $value]);
        }

        return response()->json(['error' => $data], 500);
    }
}
