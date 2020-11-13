<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * AWS Lambda allows to log to stdout and stderr, so we will use stdout for all messages and stderr for some serious issues
 *
 * @see https://docs.aws.amazon.com/lambda/latest/dg/python-logging.html
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class AwsLogger implements LoggerInterface
{
    /**
     * @var string
     */
    private const STANDARD_STREAM = 'php://stdout';

    /**
     * Stream for all errors
     * @var resource
     */
    protected $stdout;

    /**
     * AwsLogger constructor.
     */
    public function __construct()
    {
        $this->stdout = fopen(self::STANDARD_STREAM, 'a');

    }

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        $message = $this->interpolate($message, $context);

        fwrite($this->stdout, $message.PHP_EOL);
    }

    /**
     * The message MAY contain placeholders which implementors MAY replace with values from the context array.
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function interpolate(string $message, array $context = []): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }

}