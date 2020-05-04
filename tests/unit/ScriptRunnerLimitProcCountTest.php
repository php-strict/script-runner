<?php
use \PhpStrict\ScriptRunner\ScriptRunner;

class TestScriptRunner extends ScriptRunner
{
    public const PROCESSES_COUNT_DEFAULT = 1;
    public const PROCESSES_COUNT_LIMIT = 100;
    public function getProcCount(): int
    {
        return $this->procCount;
    }
}

class ScriptRunnerLimitProcCountTest extends \Codeception\Test\Unit
{
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
        file_put_contents('/tmp/test-sr-script.php', '');
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php');
        $this->assertEquals($this->getSystemCpuCoresCount(), $cw->getProcCount());
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php', 0);
        $this->assertEquals($this->getSystemCpuCoresCount(), $cw->getProcCount());
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php', -1);
        $this->assertEquals(TestScriptRunner::PROCESSES_COUNT_DEFAULT, $cw->getProcCount());
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php', 1);
        $this->assertEquals(1, $cw->getProcCount());
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php', 2);
        $this->assertEquals(2, $cw->getProcCount());
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php', TestScriptRunner::PROCESSES_COUNT_LIMIT);
        $this->assertEquals(TestScriptRunner::PROCESSES_COUNT_LIMIT, $cw->getProcCount());
        
        $cw = new TestScriptRunner('/tmp/test-sr-script.php', TestScriptRunner::PROCESSES_COUNT_LIMIT + 1);
        $this->assertEquals(TestScriptRunner::PROCESSES_COUNT_DEFAULT, $cw->getProcCount());
        
        unlink('/tmp/test-sr-script.php');
    }
}
