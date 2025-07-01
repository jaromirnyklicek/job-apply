<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Job\FetchJobDetailUseCase;
use App\Application\Job\FetchJobsUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController
{
    #[Route('/api/jobs', name: 'api_jobs_list', methods: ['GET'])]
    public function list(FetchJobsUseCase $useCase): JsonResponse
    {
        $jobs = $useCase->execute();

        $data = array_map(fn($job) => [
            'id' => $job->id,
            'title' => $job->title,
            'description' => nl2br($job->description),
        ], $jobs);

        return new JsonResponse($data);
    }

    #[Route('/api/jobs/{id}', name: 'api_jobs_detail', methods: ['GET'])]
    public function detail(string $id, FetchJobDetailUseCase $useCase): JsonResponse
    {
        try {
            $job = $useCase->execute($id);
            return new JsonResponse([
                'id' => $job->id,
                'title' => $job->title,
                'description' => nl2br($job->description),
            ]);
        } catch (JobNotFoundException $e) {
            return new JsonResponse(['error' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
