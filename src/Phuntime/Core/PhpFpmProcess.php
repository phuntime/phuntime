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
    private const PID_FILE_PATH = '/tmp/php-fpm.pid';

    /**
     * Where is php-fpm executable located?
     * @var string
     */
    private const FPM_EXECUTABLE_PATH = '/opt/bin/php-fpm';

    /**
     * @var resource
     */
    private $process;

    private array $pipes = [];

    public function start()
    {
        $descriptors = [
            0 => ['file', 'php://stdin', 'r'],
            1 => ['file', 'php://stdout', 'w'],
            2 => ['pipe', 'w'],
        ];

        $this->process = proc_open(
            sprintf(
                '%s  --nodaemonize --fpm-config /opt/php/php-fpm.conf',
                self::FPM_EXECUTABLE_PATH
            ),
            $descriptors,
            $this->pipes
        );
        stream_set_blocking($this->pipes[2], false);
    }

    protected function stop()
    {

    }
}