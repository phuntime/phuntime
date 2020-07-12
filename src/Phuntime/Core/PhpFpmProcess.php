<?php
declare(strict_types=1);

namespace Phuntime\Core;

use Symfony\Component\Process\Process;

/**
 * @package Phuntime\Core
 * @license MIT
 */
class PhpFpmProcess
{

    /**
     * On which port the process is listening
     * @var int
     */
    public const LISTEN_PORT = 9901;

    /**
     * Where is PID File located?
     * @var string
     */
    private const PID_FILE_PATH = '/opt/php/lib/php.ini';

    /**
     * Where is php-fpm executable located?
     * @var string
     */
    private const FPM_EXECUTABLE_PATH = '/opt/bin/php/sbin/php-fpm';

    protected ?Process $process = null;

    /**
     *
     */
    public function start()
    {
        if($this->process === null) {
            $this->process = new Process([
                self::FPM_EXECUTABLE_PATH,
                '--force-stderr'
            ]);
        }

        $this->process->start();
    }

    protected function stop() {

    }
}