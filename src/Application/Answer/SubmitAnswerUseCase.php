<?php

declare(strict_types=1);

namespace App\Application\Answer;

use App\Domain\Recruitis\DTO\AnswerRequestDto;
use App\Domain\Recruitis\RecruitisApiClientInterface;

final class SubmitAnswerUseCase
{
    public function __construct(
        private readonly RecruitisApiClientInterface $client,
    ) {
    }

    /**
     * @return array{status: int, body: array<string, string>}
     */
    public function execute(AnswerRequestDto $dto): array
    {
        return $this->client->postAnswer($dto);
    }
}
