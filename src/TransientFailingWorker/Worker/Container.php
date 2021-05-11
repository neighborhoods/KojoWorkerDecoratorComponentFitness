<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\TransientFailingWorker\Worker;

use Neighborhoods\DependencyInjectionContainerBuilderComponent\SymfonyConfigCacheHandler;
use Neighborhoods\DependencyInjectionContainerBuilderComponent\TinyContainerBuilder;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\TransientFailingWorker\Worker as Worker;
use Neighborhoods\Kojo\Api;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Psr\Container\ContainerInterface as PsrContainerInterface;

final class Container implements ContainerInterface
{
    private $wrappedContainer;

    private function buildWrappedContainer(): PsrContainerInterface
    {
        $rootDirectory = realpath(dirname(__DIR__, 3));
        if (false === $rootDirectory) {
            throw new RuntimeException('Absolute path of the root directory not found.');
        }

        $cacheHandler = (new SymfonyConfigCacheHandler\Builder())
            ->setName(str_replace('\\', '', Worker::class))
            ->setCacheDirPath($rootDirectory . '/data/cache')
            ->setDebug(false)
            ->build();

        return (new TinyContainerBuilder())
            ->setContainerBuilder(new ContainerBuilder())
            ->setRootPath($rootDirectory)
            ->addSourcePath('vendor/neighborhoods/throwable-diagnostic-component/fab')
            ->addSourcePath('vendor/neighborhoods/throwable-diagnostic-component/src')
            ->addSourcePath('vendor/neighborhoods/kojo-worker-decorator-component/fab')
            ->addSourcePath('vendor/neighborhoods/kojo-worker-decorator-component/src')
            ->addSourcePath('fab/Prefab5/Doctrine')
            ->addSourcePath('fab/Prefab5/PDO')
            ->addSourcePath('fab/Prefab5/Opcache')
            ->addSourcePath('src/TransientFailingWorker')
            ->addSourcePath('buphalo-fab/TransientFailingWorker')
            ->makePublic(Builder\FactoryInterface::class)
            ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass())
            ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass())
            ->setCacheHandler($cacheHandler)
            ->build();
    }

    private function getWrappedContainer(): PsrContainerInterface
    {
        if (!isset($this->wrappedContainer)) {
            $this->wrappedContainer = $this->buildWrappedContainer();
        }
        return $this->wrappedContainer;
    }

    public function getTransientFailingWorkerWorkerBuilderFactory(): Builder\FactoryInterface
    {
        return $this->getWrappedContainer()->get(Builder\FactoryInterface::class);
    }
}
