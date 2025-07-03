<?php

declare(strict_types=1);

namespace App\Application\Job;

use App\Domain\Model\Job\Job;
use App\Domain\Recruitis\RecruitisApiClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class FetchJobDetailUseCase
{
    public function __construct(private readonly RecruitisApiClientInterface $apiClient, private readonly CacheInterface $cache)
    {
    }

    public function execute(string $id): Job
    {
        return $this->cache->get('recruitis_job_' . $id, function (ItemInterface $item) use ($id): Job {
            $item->expiresAfter(15 * 60);

            $dto = $this->apiClient->fetchJobDetail($id);
            return new Job(
                id: (int)$dto->jobId,
                title: $dto->title,
                description: $dto->description
            );
        });
    }
}
