<?php
use \PhpStrict\ScriptRunner\ScriptRunner;

class ScriptRunnerRunTest extends \Codeception\Test\Unit
{
    public function testRun()
    {
        $tmpscript = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test-sr-script.php';
        
        file_put_contents($tmpscript, '<?php echo "TEST SCRIPT RUNNING";' . PHP_EOL);
        
        $sr = new ScriptRunner($tmpscript, 1);
        ob_start();
        $sr->run();
        $this->assertTrue(false !== strpos(ob_get_clean(), 'TEST SCRIPT RUNNING'));
        
        unlink($tmpscript);
    }
}
