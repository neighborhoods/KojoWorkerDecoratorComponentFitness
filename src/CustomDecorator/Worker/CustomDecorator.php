<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\CustomDecorator\Worker;

use Neighborhoods\KojoWorkerDecoratorComponent\WorkerDecorationV1\Worker\DecoratorTrait;
use Neighborhoods\KojoWorkerDecoratorComponent\WorkerDecorationV1\WorkerInterface;

final class CustomDecorator implements CustomDecoratorInterface
{
    use DecoratorTrait;

    public function work(): WorkerInterface
    {
        $this->prepare();
        try {
            $this->runWorker();
        } finally {
            $this->cleanUp();
        }

        return $this;
    }

    private function prepare(): void
    {
        $this->getApiV1WorkerService()
            ->getLogger()
            ->info('Decorator prepared everything, let\'s run the worker');
    }

    private function cleanUp(): void
    {
        $this->getApiV1WorkerService()
            ->getLogger()
            ->info('Worker finished, decorator cleaning up');
    }
}
