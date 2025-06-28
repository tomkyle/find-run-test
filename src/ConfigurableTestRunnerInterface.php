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

interface ConfigurableTestRunnerInterface extends TestRunnerInterface
{
    public function setCommand(string $cmd): self;

    public function setConfig(string $config): self;

    public function setTestsDirectory(string $dir): self;

    public function useColors(?bool $colors = null): bool|self;
}
