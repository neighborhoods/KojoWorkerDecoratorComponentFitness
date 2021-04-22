<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\DecoratorParameters;

use Neighborhoods\Kojo\Api\V1;

class Worker implements WorkerInterface
{
    use V1\Worker\Service\AwareTrait;
    use V1\RDBMS\Connection\Service\AwareTrait;

    public function work(): \Neighborhoods\KojoWorkerDecoratorComponent\WorkerV1\WorkerInterface
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
