<?php
declare(strict_types=1);

namespace Phuntime\Fpm;

use Psr\Log\LoggerInterface;

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
     * Where is php-fpm executable located?
     * @var string
     */
    private $fpmExecutablePath;

    /**
     * @var resource
     */
    private $process;

    private array $pipes = [];

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, string $fpmExecutablePath)
    {
        $this->logger = $logger;
        $this->fpmExecutablePath = $fpmExecutablePath;
    }

    public function start()
    {

        if(!file_exists($this->fpmExecutablePath)) {
            throw new \RuntimeException(sprintf('Could not find PHP FPM executable! (tried: %s)', $this->fpmExecutablePath));
        }

        /**
         * no need to run process twice if running
         */
        if($this->process !== null) {
            return;
        }

        $this->logger->info('Starting up PHP-FPM.');

        /**
         * pipes used to share logs from FPM to phuntime
         */
        $descriptors = [
            0 => ['file', 'php://stdin', 'r'],
            1 => ['file', 'php://stdout', 'w'],
            2 => ['pipe', 'w'],
        ];

        /**
         * Creates a handler FPM Process
         */
        $this->process = proc_open(
            sprintf(
                '%s  --nodaemonize --fpm-config /opt/php/php-fpm.conf',
                $this->fpmExecutablePath
            ),
            $descriptors,
            $this->pipes
        );

        $this->logger->debug('Setting fpm process pipes non-blocking.');
        /**
         * Do not wait for logs from FPM, just continue if nothing passed
         * It must be before the while loop otherwise it would be hard to determine that process is running.
         */
        stream_set_blocking($this->pipes[2], false);


        /**
         * proc_open() only can tell if process is running, but this does not mean it can handle connections
         * so we need to look for phrase in stdout to ensure fpm is fully loaded and ready (or not) for adventure
         */
        $isReady = false;
        $readyPhraseToCatch = 'ready to handle connections';
        $failedPhraseToCatch = 'FPM initialization failed';
        $this->logger->debug('Waiting for fpm to be ready.');

        while ($isReady === false) {
            $logs = $this->popFpmLogs();
            $this->logFpmLogs($logs);
            $isReady = stripos($logs, $readyPhraseToCatch) !== false;
            $initFailed = is_int(stripos($logs, $failedPhraseToCatch));

            if($initFailed) {
                throw new \RuntimeException('Unable to run php-fpm!');
            }
        }

        $this->logger->info('FPM is ready for handling requests.');
    }


    /**
     * Returns output from FPM
     * @return string|null
     */
    protected function popFpmLogs(): ?string
    {
        return stream_get_contents($this->pipes[2]);
    }

    /**
     * checks with each tick that fpm is still working.
     */
    protected function checkProcessStatus():void
    {
        $status = proc_get_status($this->process);
        if(!$status['running']) {
            $this->logger->warning('php-fpm stopped running for some reason!');
            //@TODO maybe throw an exception here?
        }
    }

    public function tick(): void
    {
        $this->checkProcessStatus();
        $logs = $this->popFpmLogs();

        if($logs !== null) {
            $this->logFpmLogs($logs);
        }
    }

    protected function logFpmLogs(string $logs): void
    {
        if($logs === '') {
            return;
        }

        $this->logger->info(sprintf('FPM stdout: %s', $logs));
    }
    protected function stop()
    {

    }
}