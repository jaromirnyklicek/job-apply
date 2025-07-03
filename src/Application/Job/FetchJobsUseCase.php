<?php

declare(strict_types=1);

namespace App\Application\Job;

use App\Domain\Model\Job\Job;
use App\Domain\Recruitis\DTO\JobDto;
use App\Domain\Recruitis\RecruitisApiClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FetchJobsUseCase
{
    public function __construct(private readonly RecruitisApiClientInterface $apiClient, private readonly CacheInterface $cache)
    {
    }

    /** @return Job[] */
    public function execute(): array
    {
        return $this->cache->get('recruitis_jobs_all', function (ItemInterface $item): array {
            $item->expiresAfter(15 * 60);

            return array_map(
                function (JobDto $dto): Job {
                    return new Job(
                        id: (int) $dto->jobId,
                        title: $dto->title,
                        description: $dto->description
                    );
                },
                $this->apiClient->fetchJobs()
            );
        });
    }
}
