<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class AviationStackService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.aviationstack.api_key');
    }

    public function getFlightDetails($flightNumber, $flightDate)
    {
        try {
            // Initialiser une session cURL
            $ch = curl_init();

            // Définir l'URL et les options de cURL
            $url = 'http://api.aviationstack.com/v1/flights?access_key=' . $this->apiKey . '&flight_iata=' . $flightNumber . '&flight_date=' . $flightDate;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);

            // Exécuter la requête cURL et obtenir la réponse
            $response = curl_exec($ch);

            // Vérifier s'il y a des erreurs cURL
            if (curl_errno($ch)) {
                throw new \Exception('cURL error: ' . curl_error($ch));
            }

            // Fermer la session cURL
            curl_close($ch);

            // Décoder la réponse JSON
            $data = json_decode($response, true);

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