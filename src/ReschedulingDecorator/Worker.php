<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\ReschedulingDecorator;

use Neighborhoods\Kojo\Api\V1;

class Worker implements WorkerInterface
{
    use V1\Worker\Service\AwareTrait;
    use V1\RDBMS\Connection\Service\AwareTrait;

    public function work(): \Neighborhoods\KojoWorkerDecoratorComponent\WorkerDecorationV1\WorkerInterface
    {
        $this->getApiV1WorkerService()
            ->getLogger()
            ->info(static::class . ' doesn\'t complete with success to let decorator reschedule it.');

        return $this;
    }
}
