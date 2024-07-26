<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenSkyService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://api.aviationstack.com/v1/',
            'timeout'  => 2.0,
        ]);
        $this->apiKey = config('services.aviationstack.api_key');
    }

    public function getFlightDetails($flightNumber)
    {
        try {
            $response = $this->client->request('GET', 'flights', [
                'query' => [
                    'access_key' => $this->apiKey,
                    'flight_iata' => $flightNumber,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Log the raw response for debugging
            Log::info('AviationStack API Response', $data);

            return $data;
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('AviationStack API Error', ['error' => $e->getMessage()]);
            return [];
        }
    }
}