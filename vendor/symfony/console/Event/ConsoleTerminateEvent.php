<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211124\Symfony\Component\Console\Event;

use MonorepoBuilder20211124\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20211124\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface;
/**
 * Allows to manipulate the exit code of a command after its execution.
 *
 * @author Francesco Levorato <git@flevour.net>
 */
final class ConsoleTerminateEvent extends \MonorepoBuilder20211124\Symfony\Component\Console\Event\ConsoleEvent
{
    private $exitCode;
    public function __construct(\MonorepoBuilder20211124\Symfony\Component\Console\Command\Command $command, \MonorepoBuilder20211124\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface $output, int $exitCode)
    {
        parent::__construct($command, $input, $output);
        $this->setExitCode($exitCode);
    }
    /**
     * @param int $exitCode
     */
    public function setExitCode($exitCode) : void
    {
        $this->exitCode = $exitCode;
    }
    public function getExitCode() : int
    {
        return $this->exitCode;
    }
}
