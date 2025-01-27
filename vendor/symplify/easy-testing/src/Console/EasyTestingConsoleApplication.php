<?php

declare (strict_types=1);
namespace MonorepoBuilder20211124\Symplify\EasyTesting\Console;

use MonorepoBuilder20211124\Symfony\Component\Console\Application;
use MonorepoBuilder20211124\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20211124\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class EasyTestingConsoleApplication extends \MonorepoBuilder20211124\Symfony\Component\Console\Application
{
    /**
     * @param Command[] $commands
     */
    public function __construct(\MonorepoBuilder20211124\Symplify\PackageBuilder\Console\Command\CommandNaming $commandNaming, array $commands)
    {
        foreach ($commands as $command) {
            $commandName = $commandNaming->resolveFromCommand($command);
            $command->setName($commandName);
            $this->add($command);
        }
        parent::__construct('Easy Testing');
    }
}
