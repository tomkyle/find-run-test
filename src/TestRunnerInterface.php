<?php

/**
 * This file is part of tomkyle/find-run-test
 */

namespace tomkyle\FindRunTest;

interface TestRunnerInterface
{

    /**
     * Find and run test for provided source file.
     *
     * @param  string $src_file Source file
     * @return int              Exit code, usually `0`.
     */
    public function __invoke(string $src_file): int;

    /**
     * Run that unit test from provided test class.
     * @param  string $unit_test Test class file
     *
     * @return $this
     */
    public function runTest(string $unit_test): self;
}
