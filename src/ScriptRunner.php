<?php
/**
 * PHP Strict.
 * 
 * @copyright   Copyright (C) 2018 - 2020 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

declare(strict_types=1);

namespace PhpStrict\ScriptRunner;

/**
 * Class for running PHP CLI script in several separate processes.
 * Also may be used with https://github.com/php-strict/cooperative-worker 
 * to run script with its instance several times.
 * It is possible to extend this class and redefine const PROCESS_COMMAND
 * to execute some different from php command, but on your own risk.
 */
class ScriptRunner
{
    /**
     * @var int
     */
    protected const PROCESSES_COUNT_DEFAULT = 2;
    
    /**
     * @var int
     */
    protected const PROCESSES_COUNT_LIMIT = 128;
    
    /**
     * @var string
     */
    protected const PROCESS_COMMAND = 'php -f %s';
    
    /**
     * @var int
     */
    protected const PROCESS_READ_LENGTH = 256;
    
    /**
     * @var int
     */
    protected const PROCESSES_POLLING_INTERVAL = 1000;
    
    /**
     * @var int
     */
    protected $procCount = 0;
    
    /**
     * @var array
     */
    protected $procHandles = [];
    
    /**
     * @var string
     */
    protected $runScript = '';
    
    /**
     * @param string $runScript     path to script to run
     * @param int $procCount = 0    count of running processes, if omitted then system CPU cores count will be used
     */
    public function __construct(string $runScript, int $procCount = 0)
    {
        $this->procCount = $procCount;
        if (0 == $procCount) {
            $this->procCount = $this->getSystemCpuCoresCount();
        }
        $this->limitProcCount();
        
        if (!file_exists($runScript)) {
            throw new \Exception('Run script not exists');
        }
        $this->runScript = $runScript;
    }
    
    /**
     * @param bool $silent = false  disable output from running scripts
     * @return void
     */
    public function run(bool $silent = false): void
    {
        for ($i = 0; $i < $this->procCount; $i++) {
            echo 'Run script #' . $i . '... ';
            $handle = popen(sprintf(static::PROCESS_COMMAND, $this->runScript), 'r');
            if (is_resource($handle)) {
                $this->procHandles[] = [$i, $handle];
                stream_set_blocking($handle, false); //not work on Windows
                echo 'OK' . PHP_EOL;
                continue;
            }
            echo 'FAIL' . PHP_EOL;
        }
        
        do {
            for ($i = 0, $cnt = count($this->procHandles); $i < $cnt; $i++) {
                $out = fread($this->procHandles[$i][1], self::PROCESS_READ_LENGTH);
                
                if (!$silent) {
                    echo $out;
                }
                
                if (feof($this->procHandles[$i][1])) {
                    echo 'Close script #' . $this->procHandles[$i][0] . '... ';
                    pclose($this->procHandles[$i][1]);
                    echo 'OK' . PHP_EOL;
                    
                    unset($this->procHandles[$i]);
                    $this->procHandles = array_values($this->procHandles);
                    
                    break;
                }
            }
            
            usleep(self::PROCESSES_POLLING_INTERVAL);
            
        } while (0 < count($this->procHandles));
    }
    
    /**
     * @return void
     */
    protected function limitProcCount(): void
    {
        if (0 >= $this->procCount || self::PROCESSES_COUNT_LIMIT < $this->procCount) {
            $this->procCount = self::PROCESSES_COUNT_DEFAULT;
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
}
