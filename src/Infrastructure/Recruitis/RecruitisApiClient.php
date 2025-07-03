<?php

declare(strict_types=1);

namespace App\Infrastructure\Recruitis;

use App\Domain\Recruitis\DTO\AnswerRequestDto;
use App\Domain\Recruitis\DTO\JobDto;
use App\Domain\Recruitis\RecruitisApiClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecruitisApiClient implements RecruitisApiClientInterface
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

        $payload = $response->toArray()['payload'] ?? [];

        return array_map(fn (array $job) => JobDto::fromArray($job), $payload);
    }

    public function fetchJobDetail(string $id): JobDto
    {
        $url = sprintf('%s/jobs/%s', self::API_BASE_URL, $id);

        $response = $this->httpClient->request('GET', $url, [
            'headers' => $this->authHeaders(),
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('Detail not found.');
        }

        $payload = $response->toArray()['payload'] ?? [];
        if ([] === $payload) {
            throw new \RuntimeException('Server returned empty payload.');
        }

        return JobDto::fromArray($payload);
    }

    public function postAnswer(AnswerRequestDto $dto): array
    {
        $payload = $dto->toArray();

        $response = $this->httpClient->request(
            'POST',
            sprintf('%s/answers', self::API_BASE_URL),
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
            'Authorization' => 'Bearer '.$this->token,
        ];
    }
}
