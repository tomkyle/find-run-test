<?php

namespace tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\ProcessFailedException;
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
        $sut = new PhpUnitRunner();
        $this->assertInstanceOf(TestRunnerInterface::class, $sut);
        $this->assertInstanceOf(ConfigurableTestRunnerInterface::class, $sut);
    }

    /**
     * Test that the constructor correctly initializes the class properties
     */
    public function testConstructorInitializesProperties(): void
    {
        $sut = new PhpUnitRunner('custom_config.xml', '/path/to/tests', '/custom/path/phpunit');

        $this->assertEquals('custom_config.xml', $sut->getConfig());
        $this->assertSame('/custom/path/phpunit', $sut->getCommand());
        $this->assertSame('/path/to/tests', $sut->getTestsDirectory());
    }


    /**
     * Test the invocation method returns 0 if no test files are found.
     */
    public function testSuccessExitEvenWhenNoTestFilesFound(): void
    {
        $sut = new PhpUnitRunner(tests: $this->temporaryDirectory->path());

        $this->assertEquals(0, $sut->__invoke('sample.php'));
    }


    /**
     * Test the invocation method throws an exception if the process fails.
     */
    public function testExceptionOnProcessFailureWhenInvoked(): void
    {
        $sut = new PhpUnitRunner(tests: $this->temporaryDirectory->path());

        $this->expectException(\RuntimeException::class);
        $this->expectException(ProcessFailedException::class);

        // This should cause the Process to fail
        $sut->setCommand("does/not/exist");

        // Mock a changed source file and the matching test file
        $src_mock = tempnam($this->temporaryDirectory->path(), 'file');
        $test_mock = $src_mock.'Test.php';
        copy($src_mock, $test_mock);

        $sut->__invoke($src_mock);
    }


    /**
     * Test the invocation method throws an exception if the process fails.
     */
    public function testExceptionOnProcessFailureWithRunTestMethod(): void
    {
        $sut = new PhpUnitRunner(tests: $this->temporaryDirectory->path());

        $this->expectException(\RuntimeException::class);
        $this->expectException(ProcessFailedException::class);

        // This should cause the Process to fail
        $sut->setCommand("does/not/exist");

        // Mock a changed source file and the matching test file
        $src_mock = tempnam($this->temporaryDirectory->path(), 'file');
        $test_mock = $src_mock.'Test.php';
        copy($src_mock, $test_mock);

        $sut->runTest($test_mock);
    }


    public function testUseColorsInterceptor() : void
    {
        $sut = new PhpUnitRunner();
        $sut->useColors(true);
        $this->assertEquals(true, $sut->useColors());

        $sut->useColors(false);
        $this->assertEquals(false, $sut->useColors());
    }

}

