<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Infrastructure\Recruitis\RecruitisApiClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

final class AnswerController
{
    public function __construct(private RecruitisApiClient $client)
    {
    }

    #[Route('/api/answers', name: 'api_answers_submit', methods: ['POST'])]
    public function submit(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['job_id', 'name', 'email', 'phone', 'coverLetter'];
        foreach ($requiredFields as $field) {
            if (($data[$field] ?? null) === null) {
                return new JsonResponse(['error' => "Missing field: $field"], 400);
            }
        }

        try {
            $result = $this->client->postJobAnswer(
                jobId: (int)$data['job_id'],
                name: (string)$data['name'],
                email: (string)$data['email'],
                phone: (string)$data['phone'],
                linkedin: (string)($data['linkedin'] ?? ''),
                coverLetter: (string)$data['coverLetter'],
                salary: (int)($data['salary'] ?? 0)
            );

            return new JsonResponse($result['body'], $result['status']);

        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
