<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FindCountryByCoordinates
{
    private $httpClient;
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getCountryFromCoordinates(string $latitude, string $longitude): ?string
    {
        try {
            sleep(1);
            $response = $this->httpClient->request('GET', 'https://geocode.maps.co/reverse', [
                'query' => [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'api_key' => '678f87f30b833505483462kalced1fc',
                ],
            ]);

            $data = $response->toArray();
            return $data['address']['country'] ?? 'Inconnue';
        } catch (\Exception $e) {
            // Gérer l'erreur ou la logger
            return 'Inconnue';
        }
    }
}
