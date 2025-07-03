<?php
declare(strict_types=1);

namespace App\Application\Job;

use App\Domain\Model\Job\Job;
use App\Domain\Recruitis\DTO\JobDto;
use App\Domain\Recruitis\RecruitisApiClientInterface;

class FetchJobsUseCase
{
    public function __construct(private readonly RecruitisApiClientInterface $apiClient)
    {
    }

    /** @return Job[] */
    public function execute(): array
    {
        $rawJobs = $this->apiClient->fetchJobs();

        return array_map(function (JobDto $dto): Job {
            return new Job(
                id: (int)$dto->jobId,
                title: $dto->title,
                description: $dto->description
            );
        }, $rawJobs);
    }
}
