<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\FailingWorker;

use Exception;
use Neighborhoods\Kojo\Api\V1;

class Worker implements WorkerInterface
{
    use V1\Worker\Service\AwareTrait;
    use V1\RDBMS\Connection\Service\AwareTrait;

    public function work(): \Neighborhoods\KojoWorkerDecoratorComponent\WorkerInterface
    {
        throw new Exception("The ExceptionHandlingDecorator holds the failed job.");

        return $this;
    }
}
