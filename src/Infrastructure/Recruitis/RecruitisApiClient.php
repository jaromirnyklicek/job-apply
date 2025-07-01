<?php
declare(strict_types=1);

namespace App\Infrastructure\Recruitis;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecruitisApiClient
{
    private const API_BASE_URL = 'https://app.recruitis.io/api2';

    private HttpClientInterface $httpClient;
    private string $token;

    public function __construct(HttpClientInterface $httpClient, string $token)
    {
        $this->httpClient = $httpClient;
        $this->token = $token;
    }

    public function fetchJobs(): array
    {
        $response = $this->httpClient->request('GET', sprintf('%s/jobs', self::API_BASE_URL), [
            'headers' => $this->authHeaders(),
        ]);

        return $response->toArray()['payload'] ?? [];
    }

    public function fetchJobDetail(string $id): array
    {
        $url = sprintf('%s/jobs/%s', self::API_BASE_URL, $id);

        $response = $this->httpClient->request('GET', $url, [
            'headers' => $this->authHeaders(),
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('Detail not found.');
        }

        return $response->toArray()['payload'] ?? [];
    }

    private function authHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }
}
