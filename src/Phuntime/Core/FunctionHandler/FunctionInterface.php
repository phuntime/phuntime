<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
interface FunctionInterface extends LoggerAwareInterface
{
    /**
     * @param object $event
     * @return mixed
     */
    public function handleEvent(object $event);

    public function boot();

    public function handle(ServerRequestInterface $request): ResponseInterface;
}