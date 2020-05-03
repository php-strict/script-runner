<?php
use \PhpStrict\ScriptRunner\ScriptRunner;

class ScriptRunnerConstructTest extends \Codeception\Test\Unit
{
    /**
     * @param string $expectedExceptionClass
     * @param callable $call = null
     */
    protected function expectedException(string $expectedExceptionClass, callable $call = null)
    {
        try {
            $call();
        } catch (\Exception $e) {
            $this->assertEquals($expectedExceptionClass, get_class($e));
            return;
        }
        if ('' != $expectedExceptionClass) {
            $this->fail('Expected exception not throwed');
        }
    }
    
    /**
     * @return int
     */
    protected function getSystemCpuCoresCount(): int
    {
        if (PHP_OS_FAMILY == 'Windows') {
            return (int) shell_exec('echo %NUMBER_OF_PROCESSORS%');
        }
        return (int) shell_exec('nproc');
    }
    
    public function testJobsStorageAssignment()
    {
        $this->expectedException(
            \Exception::class, 
            function() { new ScriptRunner('not-existence-script.php'); }
        );
        
        file_put_contents('/tmp/test-sr-script.php', '');
        
        $cw = 
            new class(
                '/tmp/test-sr-script.php',
                2
            ) extends ScriptRunner {
                public function getProcCount(): int
                {
                    return $this->procCount;
                }
                public function getRunScript(): string
                {
                    return $this->runScript;
                }
            };
        $this->assertEquals(2, $cw->getProcCount());
        $this->assertEquals('/tmp/test-sr-script.php', $cw->getRunScript());
        
        $cw = 
            new class(
                '/tmp/test-sr-script.php'
            ) extends ScriptRunner {
                public function getProcCount(): int
                {
                    return $this->procCount;
                }
                public function getRunScript(): string
                {
                    return $this->runScript;
                }
            };
        $this->assertEquals($this->getSystemCpuCoresCount(), $cw->getProcCount());
        
        $cw = 
            new class(
                '/tmp/test-sr-script.php',
                100
            ) extends ScriptRunner {
                public function getProcCount(): int
                {
                    return $this->procCount;
                }
                public function getRunScript(): string
                {
                    return $this->runScript;
                }
            };
        $this->assertEquals(100, $cw->getProcCount());
        
        $cw = 
            new class(
                '/tmp/test-sr-script.php',
                200
            ) extends ScriptRunner {
                public function getProcCount(): int
                {
                    return $this->procCount;
                }
                public function getRunScript(): string
                {
                    return $this->runScript;
                }
            };
        $this->assertEquals(2, $cw->getProcCount());
        
        unlink('/tmp/test-sr-script.php');
    }
}
