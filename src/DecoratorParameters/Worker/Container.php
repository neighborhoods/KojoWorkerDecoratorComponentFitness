<?php

declare(strict_types=1);

namespace Neighborhoods\KojoWorkerDecoratorComponentFitness\DecoratorParameters\Worker;

use Neighborhoods\DependencyInjectionContainerBuilderComponent\SymfonyConfigCacheHandler;
use Neighborhoods\DependencyInjectionContainerBuilderComponent\TinyContainerBuilder;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\DecoratorParameters\Worker as Worker;
use Neighborhoods\Kojo\Api;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Psr\Container\ContainerInterface as PsrContainerInterface;

final class Container implements ContainerInterface
{
    private const KOJO_WORKER_DECORATORS = [
        'WorkerV1Decorators/CrashedThresholdV1',
        'WorkerV1Decorators/ExceptionHandlingV1',
        'WorkerV1Decorators/RetryThresholdV1',
        'WorkerV1Decorators/UserlandPdoV1',
    ];
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

        $containerBuilder = (new TinyContainerBuilder())
            ->setContainerBuilder(new ContainerBuilder())
            ->setRootPath($rootDirectory)
            ->addSourcePath('fab/Prefab5/Doctrine')
            ->addSourcePath('fab/Prefab5/PDO')
            ->addSourcePath('fab/Prefab5/Opcache')
            ->addSourcePath('buphalo-fab/DecoratorParameters')
            ->addSourcePath('src/DecoratorParameters')
            ->makePublic(Builder\FactoryInterface::class)
            ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass())
            ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass())
            ->setCacheHandler($cacheHandler);

        foreach (self::KOJO_WORKER_DECORATORS as $decorator) {
            $containerBuilder
                ->addSourcePath('vendor/neighborhoods/kojo-worker-decorator-component/fab/' . $decorator)
                ->addSourcePath('vendor/neighborhoods/kojo-worker-decorator-component/src/' . $decorator);
        }

        return $containerBuilder->build();
    }

    private function getWrappedContainer(): PsrContainerInterface
    {
        if (!isset($this->wrappedContainer)) {
            $this->wrappedContainer = $this->buildWrappedContainer();
        }
        return $this->wrappedContainer;
    }

    public function getDecoratorParametersWorkerBuilderFactory(): Builder\FactoryInterface
    {
        return $this->getWrappedContainer()->get(Builder\FactoryInterface::class);
    }
}
