<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211124\Symfony\Component\Console\Logger;

use MonorepoBuilder20211124\Psr\Log\AbstractLogger;
use MonorepoBuilder20211124\Psr\Log\InvalidArgumentException;
use MonorepoBuilder20211124\Psr\Log\LogLevel;
use MonorepoBuilder20211124\Symfony\Component\Console\Output\ConsoleOutputInterface;
use MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface;
if ((new \ReflectionMethod(\MonorepoBuilder20211124\Psr\Log\AbstractLogger::class, 'log'))->hasReturnType()) {
    throw new \RuntimeException(\sprintf('The "%s" logger is not compatible with psr/log >= 3.0. Try running "composer require psr/log:^2.".', \MonorepoBuilder20211124\Symfony\Component\Console\Logger\ConsoleLogger::class));
}
/**
 * PSR-3 compliant console logger.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 *
 * @see https://www.php-fig.org/psr/psr-3/
 */
class ConsoleLogger extends \MonorepoBuilder20211124\Psr\Log\AbstractLogger
{
    public const INFO = 'info';
    public const ERROR = 'error';
    private $output;
    private $verbosityLevelMap = [\MonorepoBuilder20211124\Psr\Log\LogLevel::EMERGENCY => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL, \MonorepoBuilder20211124\Psr\Log\LogLevel::ALERT => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL, \MonorepoBuilder20211124\Psr\Log\LogLevel::CRITICAL => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL, \MonorepoBuilder20211124\Psr\Log\LogLevel::ERROR => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL, \MonorepoBuilder20211124\Psr\Log\LogLevel::WARNING => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL, \MonorepoBuilder20211124\Psr\Log\LogLevel::NOTICE => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_VERBOSE, \MonorepoBuilder20211124\Psr\Log\LogLevel::INFO => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_VERY_VERBOSE, \MonorepoBuilder20211124\Psr\Log\LogLevel::DEBUG => \MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_DEBUG];
    private $formatLevelMap = [\MonorepoBuilder20211124\Psr\Log\LogLevel::EMERGENCY => self::ERROR, \MonorepoBuilder20211124\Psr\Log\LogLevel::ALERT => self::ERROR, \MonorepoBuilder20211124\Psr\Log\LogLevel::CRITICAL => self::ERROR, \MonorepoBuilder20211124\Psr\Log\LogLevel::ERROR => self::ERROR, \MonorepoBuilder20211124\Psr\Log\LogLevel::WARNING => self::INFO, \MonorepoBuilder20211124\Psr\Log\LogLevel::NOTICE => self::INFO, \MonorepoBuilder20211124\Psr\Log\LogLevel::INFO => self::INFO, \MonorepoBuilder20211124\Psr\Log\LogLevel::DEBUG => self::INFO];
    private $errored = \false;
    public function __construct(\MonorepoBuilder20211124\Symfony\Component\Console\Output\OutputInterface $output, array $verbosityLevelMap = [], array $formatLevelMap = [])
    {
        $this->output = $output;
        $this->verbosityLevelMap = $verbosityLevelMap + $this->verbosityLevelMap;
        $this->formatLevelMap = $formatLevelMap + $this->formatLevelMap;
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     * @param mixed[] $context
     */
    public function log($level, $message, $context = [])
    {
        if (!isset($this->verbosityLevelMap[$level])) {
            throw new \MonorepoBuilder20211124\Psr\Log\InvalidArgumentException(\sprintf('The log level "%s" does not exist.', $level));
        }
        $output = $this->output;
        // Write to the error output if necessary and available
        if (self::ERROR === $this->formatLevelMap[$level]) {
            if ($this->output instanceof \MonorepoBuilder20211124\Symfony\Component\Console\Output\ConsoleOutputInterface) {
                $output = $output->getErrorOutput();
            }
            $this->errored = \true;
        }
        // the if condition check isn't necessary -- it's the same one that $output will do internally anyway.
        // We only do it for efficiency here as the message formatting is relatively expensive.
        if ($output->getVerbosity() >= $this->verbosityLevelMap[$level]) {
            $output->writeln(\sprintf('<%1$s>[%2$s] %3$s</%1$s>', $this->formatLevelMap[$level], $level, $this->interpolate($message, $context)), $this->verbosityLevelMap[$level]);
        }
    }
    /**
     * Returns true when any messages have been logged at error levels.
     *
     * @return bool
     */
    public function hasErrored()
    {
        return $this->errored;
    }
    /**
     * Interpolates context values into the message placeholders.
     *
     * @author PHP Framework Interoperability Group
     */
    private function interpolate(string $message, array $context) : string
    {
        if (\strpos($message, '{') === \false) {
            return $message;
        }
        $replacements = [];
        foreach ($context as $key => $val) {
            if (null === $val || \is_scalar($val) || \is_object($val) && \method_exists($val, '__toString')) {
                $replacements["{{$key}}"] = $val;
            } elseif ($val instanceof \DateTimeInterface) {
                $replacements["{{$key}}"] = $val->format(\DateTime::RFC3339);
            } elseif (\is_object($val)) {
                $replacements["{{$key}}"] = '[object ' . \get_class($val) . ']';
            } else {
                $replacements["{{$key}}"] = '[' . \gettype($val) . ']';
            }
        }
        return \strtr($message, $replacements);
    }
}
