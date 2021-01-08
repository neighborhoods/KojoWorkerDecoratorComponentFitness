#!/usr/bin/env php
<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Neighborhoods\KojoWorkerDecoratorComponentFitness\TransientFailingWorker;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\Prefab5\Doctrine\DBAL\Connection\DecoratorInterface;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\Prefab5\Protean\Container\Builder;

// Instantiate a new Protean (Neighborhoods) Container Builder.
$proteanContainerBuilder = (new Builder());
// Sets the name of the built container. This will be the name of the file if cached.
$proteanContainerBuilder->setContainerName('DoctrineConnectionBuilder');
// This is to turn off some unnecessary HTTP logic.
$proteanContainerBuilder->setCanBuildZendExpressive(false);
$proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('Prefab5/Doctrine');
$proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('Prefab5/PDO');
$proteanContainerBuilder->getDiscoverableDirectories()->addDirectoryPathFilter('Prefab5/Opcache');
$proteanContainerBuilder->getFilesystemProperties()->setRootDirectoryPath(realpath(dirname(__DIR__)));
// All Actors are non-public in the container by default, we turn one and ony one on to use.
$proteanContainerBuilder->registerServiceAsPublic(DecoratorInterface::class);
// Build the container.
$proteanContainer = $proteanContainerBuilder->build();

// Retrieve the only public service (that we enabled above).
$connection = $proteanContainer->get(DecoratorInterface::class)->getDoctrineConnection();

$now = (new DateTime())->format('Y-m-d H:i:s');
$connection->insert(
    'kojo_job',
    [
        'type_code' => TransientFailingWorker\WorkerInterface::JOB_TYPE_CODE,
        'name' => 'Worker failing due to transient fault',
        'priority' => 10,
        'importance' => 10,
        'work_at_date_time' => $now,
        'next_state_request' => 'working',
        'assigned_state' => 'waiting',
        'previous_state' => 'new',
        'worker_uri' => TransientFailingWorker\Worker\Proxy::class,
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