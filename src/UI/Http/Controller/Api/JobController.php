<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Job\FetchJobsUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'description' => $job->description
        ], $jobs);

        return new JsonResponse($data);
    }
}
