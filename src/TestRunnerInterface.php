<?php

namespace tomkyle\FindRunTest;

interface TestRunnerInterface
{
    public function __invoke(string $src_file) : int;

    public function runTest(string $unit_test) : self;
}
