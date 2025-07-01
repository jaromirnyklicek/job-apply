<?php
declare(strict_types=1);

namespace App\Infrastructure\Recruitis;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecruitisApiClient
{
    private HttpClientInterface $httpClient;
    private string $token;

    public function __construct(HttpClientInterface $httpClient, string $token)
    {
        $this->httpClient = $httpClient;
        $this->token = $token;
    }

    public function fetchJobs(): array
    {
        $response = $this->httpClient->request('GET', 'https://app.recruitis.io/api2/jobs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);

        return $response->toArray()['payload'] ?? [];
    }
}
