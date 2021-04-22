<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\NamedWorker;

use Neighborhoods\Kojo\Api\V1;
use Neighborhoods\KojoWorkerDecoratorComponent\WorkerV1\WorkerInterface;

class CustomWorkerName implements CustomWorkerNameInterface
{
    use V1\Worker\Service\AwareTrait;
    use V1\RDBMS\Connection\Service\AwareTrait;

    public function work(): WorkerInterface
    {
        $this->getApiV1WorkerService()
            ->getLogger()
            ->info(static::class . ' completing successfully.');

        $this->getApiV1WorkerService()
            ->requestCompleteSuccess()
            ->applyRequest();

        return $this;
    }
}
