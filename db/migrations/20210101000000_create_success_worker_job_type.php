<?php
declare(strict_types=1);

use Neighborhoods\Kojo\Api\V1\Job;
use Neighborhoods\KojoWorkerDecoratorComponentFitness\SuccessWorker as Component;
use Symfony\Component\Finder\Finder;
use Phinx\Migration\AbstractMigration;

final class CreateSuccessWorkerJobType  extends AbstractMigration
{
    public function up()
    {
        // Setup Kojo (find the environment variable file, load it, etc.)
        $discoverableDirectories[] = __DIR__ . '/../../kojo-environment';
        $finder = new Finder();
        $finder->name('*.yml');
        $finder->files()->in($discoverableDirectories);

        $jobCreator = (new Job\Type\Service())
            ->addYmlServiceFinder($finder)
            ->getNewJobTypeRegistrar();

        $jobCreator->setCode(Component\WorkerInterface::JOB_TYPE_CODE)
            ->setWorkerClassUri(Component\Worker\Proxy::class)
            ->setWorkerMethod('work')
            ->setName('Worker completing successfully')
            //  ->setCronExpression('* * * * *')
            ->setCanWorkInParallel(false)
            ->setDefaultImportance(10)
            ->setScheduleLimit(0)
            ->setIsEnabled(true)
            ->setAutoCompleteSuccess(false)
            ->setAutoDeleteIntervalDuration('PT1M');
        $jobCreator->save();
    }

    public function down()
    {
        $typeCode = Component\WorkerInterface::JOB_TYPE_CODE;
        $this->execute("DELETE FROM kojo_job_type where type_code  = '$typeCode'");
    }
}
