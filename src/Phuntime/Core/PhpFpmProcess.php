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

    protected ?Process $process = null;

    /**
     *
     */
    public function start()
    {
        shell_exec('/opt/bin/php-fpm --force-stderr --daemonize --fpm-config /opt/php/php-fpm.conf');
    }

    protected function stop() {

    }
}