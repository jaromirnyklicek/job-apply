<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Answer\SubmitAnswerUseCase;
use App\Domain\Recruitis\DTO\AnswerRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AnswerController
{
    public function __construct(private readonly SubmitAnswerUseCase $submitAnswerUseCase)
    {
    }

    #[Route('/api/answers', name: 'api_answers_submit', methods: ['POST'])]
    public function submit(Request $request): Response
    {
        /** @var array{
         *   job_id: string,
         *   name: string,
         *   email: string,
         *   phone: string,
         *   coverLetter: string,
         *   linkedin?: string,
         *   salary?: int|string
         * } $data
         */
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        $requiredFields = ['job_id', 'name', 'email', 'phone', 'coverLetter'];
        foreach ($requiredFields as $field) {
            if (($data[$field] ?? null) === null) {
                return new JsonResponse(['error' => "Missing field: $field"], 400);
            }
        }

        $dto = new AnswerRequestDto(
            jobId: (string) $data['job_id'],
            name: (string) $data['name'],
            email: (string) $data['email'],
            phone: (string) $data['phone'],
            coverLetter: (string) $data['coverLetter'],
            linkedin: (string) ($data['linkedin'] ?? ''),
            salary: (int) ($data['salary'] ?? 0)
        );

        try {
            $result = $this->submitAnswerUseCase->execute($dto);

            return new JsonResponse($result['body'], $result['status']);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
