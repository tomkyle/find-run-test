<?php

namespace tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use tomkyle\FindRunTest\PhpUnitRunner;
use tomkyle\FindRunTest\TestRunnerInterface;
use tomkyle\FindRunTest\ConfigurableTestRunnerInterface;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class PhpUnitRunnerTest extends TestCase
{
    /**
     * @var PhpUnitRunner The subject under test
     */
    protected PhpUnitRunner $sut;

    /**
     * Temp dir object from "spatie/temporary-directory"
     * @var TemporaryDirectory
     */
    private $temporaryDirectory;


    #[\Override]
    protected function setUp(): void
    {
        $this->temporaryDirectory = (new TemporaryDirectory())->create();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->temporaryDirectory->delete();
    }


    public function testCreation(): void
    {
        $phpUnitRunner = new PhpUnitRunner();
        $this->assertInstanceOf(TestRunnerInterface::class, $phpUnitRunner);
        $this->assertInstanceOf(ConfigurableTestRunnerInterface::class, $phpUnitRunner);
    }

    /**
     * Test that the constructor correctly initializes the class properties
     */
    public function testConstructorInitializesProperties(): void
    {
        $phpUnitRunner = new PhpUnitRunner('custom_config.xml', '/path/to/tests', '/custom/path/phpunit');

        $this->assertEquals('custom_config.xml', $phpUnitRunner->getConfig());
        $this->assertSame('/custom/path/phpunit', $phpUnitRunner->getCommand());
        $this->assertSame('/path/to/tests', $phpUnitRunner->getTestsDirectory());
    }


    /**
     * Test the invocation method returns 0 if no test files are found.
     */
    public function testSuccessExitEvenWhenNoTestFilesFound(): void
    {
        $phpUnitRunner = new PhpUnitRunner(tests: $this->temporaryDirectory->path());

        $this->assertEquals(0, $phpUnitRunner->__invoke('sample.php'));
    }


    /**
     * Test the invocation method throws an exception if the process fails.
     */
    public function testExceptionOnProcessFailureWhenInvoked(): void
    {
        $phpUnitRunner = new PhpUnitRunner(tests: $this->temporaryDirectory->path());
        $phpUnitRunner->useColors(Process::isTtySupported());

        $this->expectException(\RuntimeException::class);
        $this->expectException(ProcessFailedException::class);

        // This should cause the Process to fail
        $phpUnitRunner->setCommand("does/not/exist");

        // Mock a changed source file and the matching test file
        $src_mock = tempnam($this->temporaryDirectory->path(), 'file');
        $test_mock = $src_mock.'Test.php';
        copy($src_mock, $test_mock);

        $phpUnitRunner->__invoke($src_mock);
    }


    /**
     * Test the invocation method throws an exception if the process fails.
     */
    public function testExceptionOnProcessFailureWithRunTestMethod(): void
    {
        $phpUnitRunner = new PhpUnitRunner(tests: $this->temporaryDirectory->path());
        $phpUnitRunner->useColors(Process::isTtySupported());

        $this->expectException(\RuntimeException::class);
        $this->expectException(ProcessFailedException::class);

        // This should cause the Process to fail
        $phpUnitRunner->setCommand("does/not/exist");

        // Mock a changed source file and the matching test file
        $src_mock = tempnam($this->temporaryDirectory->path(), 'file');
        $test_mock = $src_mock.'Test.php';
        copy($src_mock, $test_mock);

        $phpUnitRunner->runTest($test_mock);
    }


    public function testUseColorsInterceptor() : void
    {
        $phpUnitRunner = new PhpUnitRunner();
        $phpUnitRunner->useColors(true);
        $this->assertEquals(true, $phpUnitRunner->useColors());

        $phpUnitRunner->useColors(false);
        $this->assertEquals(false, $phpUnitRunner->useColors());
    }

}

