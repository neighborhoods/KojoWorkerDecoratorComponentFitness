<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\TransientFailingWorker;

interface WorkerInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerDecorationV1\WorkerInterface
{
    public const JOB_TYPE_CODE = 'transient_failing_worker';
}
