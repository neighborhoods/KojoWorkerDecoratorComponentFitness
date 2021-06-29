<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\DecoratorParameters;

interface WorkerInterface extends \Neighborhoods\KojoWorkerDecoratorComponent\WorkerDecorationV1\WorkerInterface
{
    public const JOB_TYPE_CODE = 'decorator_parameters';
}
