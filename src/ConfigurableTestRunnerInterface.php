<?php

namespace tomkyle\FindRunTest;

interface ConfigurableTestRunnerInterface extends TestRunnerInterface
{
    public function setCommand(string $cmd) : self;

    public function setConfig(string $config) : self;

    public function setTestsDirectory(string $dir) : self;

    public function useColors(bool $colors = null) : self|bool;
}
