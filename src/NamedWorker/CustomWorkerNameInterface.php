<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\NamedWorker;

interface CustomWorkerNameInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerDecorationV1\WorkerInterface
{
    public const JOB_TYPE_CODE = 'custom_worker_name';
}
