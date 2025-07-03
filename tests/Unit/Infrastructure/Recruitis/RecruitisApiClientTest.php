<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Recruitis;

use App\Infrastructure\Recruitis\RecruitisApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class RecruitisApiClientTest extends TestCase
{
    public function testFetchJobs(): void
    {
        $path = codecept_data_dir('jobs.json');
        $jobs = json_decode(file_get_contents($path), true);

        // sometimes mocking isn't that bad ;)
        $response = new MockResponse(json_encode([
            'payload' => $jobs,
        ]));

        $client = new MockHttpClient($response);

        $apiClient = new RecruitisApiClient($client, 'someSecretToken');
        $jobs = $apiClient->fetchJobs();

        $this->assertCount(3, $jobs);
        $this->assertSame('Fullstack dev', $jobs[2]->title);
    }
}
