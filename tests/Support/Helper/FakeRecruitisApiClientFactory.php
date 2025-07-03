<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

use App\Domain\Recruitis\DTO\AnswerRequestDto;
use App\Domain\Recruitis\DTO\JobDto;
use App\Domain\Recruitis\RecruitisApiClientInterface;

final class FakeRecruitisApiClientFactory
{
    public static function withFakeJobs(): RecruitisApiClientInterface
    {
        $path = codecept_data_dir('jobs.json');
        $data = json_decode(file_get_contents($path), true);

        $jobDtos = array_map(fn ($item) => new JobDto($item['job_id'], $item['title'], $item['description']), $data);

        return new class($jobDtos) implements RecruitisApiClientInterface {
            /**
             * @param JobDto[] $jobs
             */
            public function __construct(private array $jobs)
            {
            }

            public function fetchJobs(): array
            {
                return $this->jobs;
            }

            public function fetchJobDetail(string $id): JobDto
            {
                foreach ($this->jobs as $job) {
                    if ($job->jobId === $id) {
                        return $job;
                    }
                }
                throw new \RuntimeException('Detail not found');
            }

            public function postAnswer(AnswerRequestDto $dto): array
            {
                throw new \RuntimeException('Not implemented in test');
            }
        };
    }
}
