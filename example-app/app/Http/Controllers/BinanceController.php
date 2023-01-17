<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BinanceController extends Controller
{
    public function getData(Request $request)
    {
        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://api.binance.com']);

        // Send a request to the Binance API
        $response = $client->request('GET', '/api/v3/ticker/price');

        // Get the response body as a string
        $data = $response->getBody()->getContents();

        // Decode the JSON string into an array
        $data = json_decode($data, true);

        usort($data, function($a, $b) {
            return strcmp($a['symbol'], $b['symbol']);
        });

        // Pass the data to the view
        return view('binanceApi.binance', [
            'data' => $data,
        ]);
        // Return the data as a response
        // return response()->json($data);
    }

    public function showSymbol(Request $request)
    {
        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://api.binance.com']);

        // Send a request to the Binance API
        $response = $client->request('GET', '/api/v3/klines', [
            'query' => [
                'symbol' => $request->symbol,
                'interval' => $request->interval
            ]
        ]);

        // Get the response body as a string
        $data = $response->getBody()->getContents();

        // Decode the JSON string into an array
        $data = json_decode($data, true);

        // Pass the data to the view
        return view('binanceApi.chart', [
            'data' => $data,
            'label' => $request->symbol,
        ]);
    }

}
