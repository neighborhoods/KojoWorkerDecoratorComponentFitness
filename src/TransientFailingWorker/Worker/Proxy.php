<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\TransientFailingWorker\Worker;

use Neighborhoods\DependencyInjectionContainerBuilderComponent\SymfonyConfigCacheHandler;
use Neighborhoods\DependencyInjectionContainerBuilderComponent\TinyContainerBuilder;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\TransientFailingWorker\Worker as Worker;
use Neighborhoods\Kojo\Api;
use Neighborhoods\KojoWorkerDecoratorComponent\WorkerInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
        $rootDirectory = realpath(dirname(__DIR__, 3));
        if (false === $rootDirectory) {
            throw new RuntimeException('Absolute path of the root directory not found.');
        }

        $cacheHandler = (new SymfonyConfigCacheHandler\Builder())
            ->setName(str_replace('\\', '', Worker::class))
            ->setCacheDirPath($rootDirectory . '/data/cache')
            ->setDebug(true)
            ->build();

        return (new TinyContainerBuilder())
            ->setContainerBuilder(new ContainerBuilder())
            ->setRootPath($rootDirectory)
            ->addSourcePath('vendor/neighborhoods/kojo-worker-decorator-component/fab')
            ->addSourcePath('vendor/neighborhoods/kojo-worker-decorator-component/src')
            ->addSourcePath('fab/Prefab5/Doctrine')
            ->addSourcePath('fab/Prefab5/PDO')
            ->addSourcePath('fab/Prefab5/Opcache')
            ->addSourcePath('fab/Prefab5/SearchCriteria')
            ->addSourcePath('src/TransientFailingWorker')
            ->addSourcePath('buphalo-fab/TransientFailingWorker')
            ->makePublic(Worker\Builder\FactoryInterface::class)
            ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass())
            ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass())
            ->setCacheHandler($cacheHandler)
            ->build();
    }
}
