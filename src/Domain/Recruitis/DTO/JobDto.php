<?php

declare(strict_types=1);

namespace App\Domain\Recruitis\DTO;

final class JobDto
{
    public function __construct(
        public readonly string $jobId,
        public readonly string $title,
        public readonly string $description,
    ) {
    }

    /**
     * @param array<string, string|int> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            jobId: (string) ($data['job_id'] ?? ''),
            title: (string) ($data['title'] ?? ''),
            description: (string) ($data['description'] ?? ''),
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'job_id' => $this->jobId,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
