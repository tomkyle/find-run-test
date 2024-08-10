<?php

/**
 * This file is part of tomkyle/find-run-test
 */

namespace tomkyle\FindRunTest;

interface TestRunnerInterface
{
    public function __invoke(string $src_file): int;

    public function runTest(string $unit_test): self;
}
