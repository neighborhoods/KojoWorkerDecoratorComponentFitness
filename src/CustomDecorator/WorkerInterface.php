<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\CustomDecorator;

interface WorkerInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerV1\WorkerInterface
{
    public const JOB_TYPE_CODE = 'custom_decorator_worker';
}
