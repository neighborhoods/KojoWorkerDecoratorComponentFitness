<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\FailingWorker;

interface WorkerInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerV1\WorkerInterface
{
    public const JOB_TYPE_CODE = 'failing_worker';
}
