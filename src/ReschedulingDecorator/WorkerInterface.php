<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\ReschedulingDecorator;

interface WorkerInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerInterface
{
    public const JOB_TYPE_CODE = 'rescheduling_decorator_worker';
}
