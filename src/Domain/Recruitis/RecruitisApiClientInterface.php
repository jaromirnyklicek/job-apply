<?php

declare(strict_types=1);

namespace App\Domain\Recruitis;

use App\Domain\Recruitis\DTO\AnswerRequestDto;
use App\Domain\Recruitis\DTO\JobDto;

interface RecruitisApiClientInterface
{
    /**
     * @return JobDto[]
     */
    public function fetchJobs(): array;

    /**
     * @throws \RuntimeException
     */
    public function fetchJobDetail(string $id): JobDto;

    /**
     * @return array{status: int, body: array<string, string>} raw API response
     *
     * @throws \RuntimeException
     */
    public function postAnswer(AnswerRequestDto $dto): array;
}
