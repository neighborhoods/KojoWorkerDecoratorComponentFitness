<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InstallKojo extends AbstractMigration
{
    public function up()
    {
        $this->executeKojoCommand('db:setup:install');
    }

    public function down()
    {
        $this->executeKojoCommand('db:tear_down:uninstall');
    }

    protected function executeKojoCommand(string $command)
    {
        $output = [];
        $exitCode = 0;
        $appDir =  dirname(__DIR__, 2);

        exec(
            "$appDir/vendor/bin/kojo $command $appDir/kojo-environment",
            $output,
            $exitCode
        );

        if ($exitCode !== 0) {
            throw new \RuntimeException('Unable to run kojo command: ' . $command);
        }
    }
}