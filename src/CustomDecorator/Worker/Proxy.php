<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\CustomDecorator\Worker;

use Neighborhoods\KojoWorkerDecoratorComponentFitness\CustomDecorator\Worker as Worker;
use Neighborhoods\ContainerBuilder\Builder;
use Neighborhoods\Kojo\Api;
use Neighborhoods\KojoWorkerDecoratorComponent\WorkerInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;

class Proxy implements ProxyInterface
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    public function work(): WorkerInterface
    {
        $worker = $this->getContainer()->get(Worker\Builder\FactoryInterface::class)
            ->create()
            ->setApiV1RDBMSConnectionService($this->getApiV1RDBMSConnectionService())
            ->setApiV1WorkerService($this->getApiV1WorkerService())
            ->build();

        $worker->work();

        return $this;
    }

    protected function getContainer(): ContainerInterface
    {
        $proteanContainerBuilder = new Builder();
        $proteanContainerBuilder->setCanBuildZendExpressive(false);
        $proteanContainerBuilder->setContainerName(str_replace('\\', '', Worker::class));

        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter(
            '../vendor/neighborhoods/throwable-diagnostic-component/src'
        );
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter(
            '../vendor/neighborhoods/kojo-worker-decorator-component/fab'
        );
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter(
            '../vendor/neighborhoods/kojo-worker-decorator-component/src'
        );
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('../fab/Prefab5/Doctrine');
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('../fab/Prefab5/PDO');
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('../fab/Prefab5/Opcache');
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter(
            '../fab/Prefab5/SearchCriteria'
        );
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter(
            '../buphalo-fab/CustomDecorator'
        );
        $proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('CustomDecorator');
        $rootDirectory = realpath(dirname(__DIR__, 3));
        if (false === $rootDirectory) {
            throw new RuntimeException('Absolute path of the root directory not found.');
        }
        $proteanContainerBuilder->getFilesystemProperties()->setRootDirectoryPath($rootDirectory);
        $proteanContainerBuilder->registerServiceAsPublic(Worker\Builder\FactoryInterface::class);
        return $proteanContainerBuilder->build();
    }
}
