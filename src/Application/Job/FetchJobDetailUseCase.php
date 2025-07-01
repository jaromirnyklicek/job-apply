<?php

declare(strict_types=1);

namespace App\Application\Job;

use App\Domain\Job\Job;
use App\Infrastructure\Recruitis\RecruitisApiClient;

final class FetchJobDetailUseCase
{
    public function __construct(private RecruitisApiClient $client)
    {
    }

    public function execute(string $id): Job
    {
        $raw = $this->client->fetchJobDetail($id);

        return new Job(
            id: (int)$raw['job_id'],
            title: $raw['title'] ?? '',
            description: $raw['description'] ?? ''
        );
    }
}
