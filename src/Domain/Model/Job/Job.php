<?php
declare(strict_types=1);

namespace App\Domain\Model\Job;

class Job
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description
    ) {}
}
