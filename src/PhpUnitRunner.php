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

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PhpUnitRunner implements ConfigurableTestRunnerInterface
{
    /**
     * @var string
     */
    public $tests_directory;

    /**
     * @var string
     */
    public $phpunit_cmd = 'vendor/bin/phpunit';

    /**
     * @var null|string
     */
    public $phpunit_config;

    /**
     * @var bool
     */
    public $use_colors = true;

    /**
     * @param null|string $config  Optional: Custom PhpUnit config file
     * @param null|string $tests   Optional: Tests directory, default: current work dir
     * @param null|string $command Optional: Path to PhpUnit executable
     */
    public function __construct(?string $config = null, ?string $tests = null, ?string $command = null)
    {
        $this->phpunit_config = $config;
        $this->setTestsDirectory(($tests ?: getcwd()) ?: '.');
        $this->setCommand($command ?: $this->phpunit_cmd);
    }

    /**
     * @throws ProcessFailedException on Process error
     */
    #[\Override]
    public function __invoke(string $file): int
    {
        // Construct the expected name of the test file
        $file_basename = pathinfo($file, PATHINFO_FILENAME);
        $test_file = $file_basename.'Test';

        // Search for the test file in the 'tests' directory.
        $finder = (new Finder())
            ->in($this->tests_directory)
            ->files()
            ->name($test_file.'.php')
        ;

        // If no test file is found, inform the user.
        if (iterator_count($finder) < 1) {
            $msg = sprintf('No test available: %s', $file);

            echo $this->use_colors
            ? "\033[33m".$msg."\033[0m".PHP_EOL
            : $msg.PHP_EOL;

            return 0;
        }

        // Execute any found Unit tests
        foreach ($finder as $file) {
            $current_test = pathinfo($file, \PATHINFO_FILENAME);
            $this->runTest($current_test);
        }

        return 0;
    }

    /**
     * {@inheritDoc}
     *
     * Executes PhpUnit with the given Unit test file.
     *
     * @param string $unit_test Example: `MyClassTest.php`
     */
    #[\Override]
    public function runTest(string $unit_test): self
    {
        $process_args = array_filter([
            $this->phpunit_cmd,
            $this->phpunit_config ? '--configuration' : null,
            $this->phpunit_config ?: null,
            // '--no-logging',
            '--no-coverage',
            '--filter',
            $unit_test,
        ]);

        $process = (new Process($process_args))
            ->setTty($this->use_colors)
        ;
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();

        return $this;
    }

    /**
     * Sets the tests directory.
     */
    #[\Override]
    public function setTestsDirectory(string $dir): self
    {
        $this->tests_directory = $dir;

        return $this;
    }

    /**
     * Returns the tests directory.
     */
    public function getTestsDirectory(): string
    {
        return $this->tests_directory;
    }

    /**
     * Returns the PhpUnit executable.
     */
    public function getCommand(): string
    {
        return $this->phpunit_cmd;
    }

    /**
     * @param string $cmd Path to PhpUnit executable
     */
    #[\Override]
    public function setCommand(string $cmd): self
    {
        $this->phpunit_cmd = $cmd;

        return $this;
    }

    /**
     * Returns the PhpUnit config file.
     */
    public function getConfig(): string
    {
        return $this->phpunit_config;
    }

    /**
     * @param string $config PhpUnit configuration file
     */
    #[\Override]
    public function setConfig(string $config): self
    {
        $this->phpunit_config = $config;

        return $this;
    }

    /**
     * @param null|bool|bool $colors Flag
     */
    #[\Override]
    public function useColors(?bool $colors = null): bool|self
    {
        if (is_null($colors)) {
            return $this->use_colors;
        }

        $this->use_colors = $colors;

        return $this;
    }
}
