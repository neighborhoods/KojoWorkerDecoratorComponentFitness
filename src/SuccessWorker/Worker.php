<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\SuccessWorker;

use Neighborhoods\Kojo\Api\V1;

class Worker implements WorkerInterface
{
    use V1\Worker\Service\AwareTrait;
    use V1\RDBMS\Connection\Service\AwareTrait;

    public function work(): \Neighborhoods\KojoWorkerDecoratorComponent\WorkerInterface
    {
        $this->getApiV1WorkerService()
            ->requestCompleteSuccess()
            ->applyRequest();

        return $this;
    }
}
