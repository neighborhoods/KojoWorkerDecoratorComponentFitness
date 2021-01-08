<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\SuccessWorker;

interface WorkerInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerInterface
{
    public const JOB_TYPE_CODE = 'success_worker';
}
