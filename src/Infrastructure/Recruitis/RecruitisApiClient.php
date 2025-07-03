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

    public function postJobAnswer(
        int $jobId,
        string $name,
        string $email,
        string $phone,
        string $linkedin,
        string $coverLetter,
        int $salary
    ): array {
        $payload = [
            'job_id' => $jobId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'cover_letter' => $coverLetter,
        ];

        if($linkedin !== '') {
            $payload['linkedin'] = $linkedin;
        }

        if ($salary > 0) {
            $payload['salary'] = [
                'amount' => $salary,
                'currency' => 'CZK',
                'unit' => 'month',
                'type' => 0,
                'note' => '',
            ];
        }

        $response = $this->httpClient->request(
            'POST',
            "https://app.recruitis.io/api2/answers",
            [
                'headers' => $this->authHeaders(),
                'json' => $payload,
            ]
        );

        return [
            'status' => $response->getStatusCode(),
            'body' => $response->toArray(false),
        ];
    }

    private function authHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }
}
