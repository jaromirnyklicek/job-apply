<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Job\FetchJobDetailUseCase;
use App\Application\Job\FetchJobsUseCase;
use App\Domain\Model\Job\Job;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class JobController
{
    #[Route('/api/jobs', name: 'api_jobs_list', methods: ['GET'])]
    public function list(FetchJobsUseCase $useCase): JsonResponse
    {
        try {
            $jobs = $useCase->execute();

            $data = array_map(fn (Job $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'description' => nl2br($job->description),
            ], $jobs);

            return new JsonResponse(['jobs' => $data]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Cannot load jobs: '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/jobs/{id}', name: 'api_jobs_detail', methods: ['GET'])]
    public function detail(string $id, FetchJobDetailUseCase $useCase): JsonResponse
    {
        try {
            $job = $useCase->execute($id);

            return new JsonResponse([
                'job' => [
                    'id' => $job->id,
                    'title' => $job->title,
                    'description' => nl2br($job->description),
                ],
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Job not found: '.$e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
