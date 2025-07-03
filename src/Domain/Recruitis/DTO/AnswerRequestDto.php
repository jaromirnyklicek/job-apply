<?php

declare(strict_types=1);

namespace App\Domain\Recruitis\DTO;

final class AnswerRequestDto
{
    public function __construct(
        public readonly string $jobId,
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $coverLetter,
        public readonly string $linkedin = '',
        public readonly int $salary = 0,
    ) {
    }

    /**
     * @return array{
     *     job_id: string,
     *     name: string,
     *     email: string,
     *     phone: string,
     *     cover_letter: string,
     *     linkedin?: string,
     *     salary?: array{
     *          amount: int,
     *          currency: string,
     *          unit: string,
     *          type: int,
     *          note: string
     *      }
     * }
     */
    public function toArray(): array
    {
        $data = [
            'job_id' => $this->jobId,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'cover_letter' => $this->coverLetter,
        ];

        if ('' !== $this->linkedin) {
            $data['linkedin'] = $this->linkedin;
        }

        if ($this->salary > 0) {
            $data['salary'] = [
                'amount' => $this->salary,
                'currency' => 'CZK',
                'unit' => 'month',
                'type' => 0,
                'note' => '',
            ];
        }

        return $data;
    }
}
