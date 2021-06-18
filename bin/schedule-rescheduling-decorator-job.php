#!/usr/bin/env php
<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Neighborhoods\KojoWorkerDecoratorComponentFitness\ReschedulingDecorator;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\Prefab5\Doctrine\DBAL\Connection\DecoratorInterface;

$container = (new \Neighborhoods\DependencyInjectionContainerBuilderComponent\TinyContainerBuilder())
    ->setContainerBuilder(new \Symfony\Component\DependencyInjection\ContainerBuilder())
    ->setRootPath(realpath(dirname(__DIR__)))
    ->addSourcePath('fab/Prefab5/Doctrine')
    ->addSourcePath('fab/Prefab5/PDO')
    ->addSourcePath('fab/Prefab5/Opcache')
    ->makePublic(DecoratorInterface::class)
    ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass())
    ->addCompilerPass(new \Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass())
    ->build();

// Retrieve the only public service (that we enabled above).
$connection = $container->get(DecoratorInterface::class)->getDoctrineConnection();

$now = (new DateTime())->format('Y-m-d H:i:s');
$connection->insert(
    'kojo_job',
    [
        'type_code' => ReschedulingDecorator\WorkerInterface::JOB_TYPE_CODE,
        'name' => 'Worker being rescheduled by Rescheduling Decorator',
        'priority' => 10,
        'importance' => 10,
        'work_at_date_time' => $now,
        'next_state_request' => 'working',
        'assigned_state' => 'waiting',
        'previous_state' => 'new',
        'worker_uri' => ReschedulingDecorator\Worker\Proxy::class,
        'worker_method' => 'work',
        'can_work_in_parallel' => 'false',
        'last_transition_date_time' => $now,
        'last_transition_micro_time' => hrtime(true),
        'times_worked' => 0,
        'times_retried' => 0,
        'times_held' => 0,
        'times_crashed' => 0,
        'times_panicked' => 0,
        'created_at_date_time' => $now,
    ]
);
