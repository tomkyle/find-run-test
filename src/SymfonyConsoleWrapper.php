<?php

/**
 * This file is part of tomkyle/find-run-test
 *
 * Find and run the PHPUnit test for a single changed PHP class file, most useful when watching the filesystem
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace tomkyle\FindRunTest;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class SymfonyConsoleWrapper
{
    public function __construct(protected TestRunnerInterface $testRunner) {}

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        if ($this->testRunner instanceof ConfigurableTestRunnerInterface) {
            $config = $input->getOption('config');
            if (is_string($config) && !empty($config)) {
                $config = (string) $config;
                $this->testRunner->setConfig($config);
            }

            $colors = Process::isTtySupported();
            $this->testRunner->useColors($colors);
        }

        $file = $input->getArgument('file');
        if (is_string($file) && !empty($file)) {
            $file = (string) $file;
        } else {
            $output->writeln('<error>No file specified.</error>');
            return 1; // Error code for no file specified
        }
        return ($this->testRunner)($file);
    }
}
