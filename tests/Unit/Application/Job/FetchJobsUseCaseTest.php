<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Job;

use App\Application\Job\FetchJobsUseCase;
use App\Domain\Model\Job\Job;
use App\Tests\Support\Helper\FakeRecruitisApiClientFactory;
use Codeception\Test\Unit;

final class FetchJobsUseCaseTest extends Unit
{
    public function testFetchJobsReturnsJobList(): void
    {
        $fakeClient = FakeRecruitisApiClientFactory::withFakeJobs();

        $useCase = new FetchJobsUseCase($fakeClient);

        $result = $useCase->execute();

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertInstanceOf(Job::class, $result[0]);
        $this->assertEquals('PHP Developer', $result[0]->title);
    }
}
