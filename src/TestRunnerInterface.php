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

interface TestRunnerInterface
{
    /**
     * Find and run test for provided source file.
     *
     * @param string $src_file Source file
     *
     * @return int exit code, usually `0`
     */
    public function __invoke(string $src_file): int;

    /**
     * Run that unit test from provided test class.
     *
     * @param string $unit_test Test class file
     *
     * @return $this
     */
    public function runTest(string $unit_test): self;
}
