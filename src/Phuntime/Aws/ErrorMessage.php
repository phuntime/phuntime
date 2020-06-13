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