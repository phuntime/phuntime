<?php
declare(strict_types=1);

namespace Phuntime\Core\Exception;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class InitializationException extends \Error
{

    /**
     * Thrown when passed something else than FunctionInterface or Closure as a handler.
     * @param mixed $type
     * @param string $file
     * @return InitializationException
     * @deprecated to be dropped soon
     */
    public static function invalidFunctionPassed($type, $file)
    {
        return new self(
            sprintf(
                'File %s should return instanceof FunctionInterface or Closure, %s returned',
                $file,
                gettype($type)
            )
        );
    }
}