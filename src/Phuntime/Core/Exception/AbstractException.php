<?php
declare(strict_types=1);

namespace Phuntime\Core\Exception;


abstract class AbstractException extends \Exception
{
    protected \Throwable $thrown;

    public function __construct(\Throwable $exception)
    {
        parent::__construct(
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getPrevious()
        );
    }

    /**
     * @return \Throwable
     */
    public function getThrown(): \Throwable
    {
        return $this->thrown;
    }
}