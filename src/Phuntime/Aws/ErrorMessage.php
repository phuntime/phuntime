<?php
declare(strict_types=1);

namespace Phuntime\Aws;

/**
 * Object oriented representation of Lambda Runtime error
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class ErrorMessage
{
    /**
     * @var string
     */
    protected string $errorType;

    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * ErrorMessage constructor.
     * @param string $errorMessage
     * @param string $errorType
     */
    protected function __construct(string $errorMessage, string $errorType)
    {
        $this->errorMessage = $errorMessage;
        $this->errorType = $errorType;
    }

    /**
     * @param \Throwable $throwable
     * @return ErrorMessage
     */
    public static function fromThrowable(\Throwable $throwable)
    {
        return new self(
            $throwable->getMessage(),
            get_class($throwable)
        );
    }

    /**
     * @return string
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

}