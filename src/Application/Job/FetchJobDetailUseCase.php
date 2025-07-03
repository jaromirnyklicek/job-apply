<?php

declare(strict_types=1);

namespace App\Application\Job;

use App\Domain\Model\Job\Job;
use App\Domain\Recruitis\RecruitisApiClientInterface;

final class FetchJobDetailUseCase
{
    public function __construct(private readonly RecruitisApiClientInterface $apiClient)
    {
    }

    public function execute(string $id): Job
    {
        $dto = $this->apiClient->fetchJobDetail($id);

        return new Job(
            id: (int)$dto->jobId,
            title: $dto->title,
            description: $dto->description
        );
    }
}
