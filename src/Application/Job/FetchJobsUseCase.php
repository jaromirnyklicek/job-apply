<?php
declare(strict_types=1);

namespace App\Application\Job;

use App\Domain\Job\Job;
use App\Infrastructure\Recruitis\RecruitisApiClient;

class FetchJobsUseCase
{
    private RecruitisApiClient $apiClient;

    public function __construct(RecruitisApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return Job[]
     */
    public function execute(): array
    {
        $rawJobs = $this->apiClient->fetchJobs();

        return array_map(function (array $item): Job {
            return new Job(
                id: (int)$item['job_id'],
                title: $item['title'] ?? '',
                description: $item['description'] ?? ''
            );
        }, $rawJobs);
    }
}
