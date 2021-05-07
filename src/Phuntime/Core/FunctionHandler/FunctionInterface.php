<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;

use Phuntime\Core\RuntimeConfiguration;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 * @deprecated potentially
 */
interface FunctionInterface
{
    /**
     * @param object $event
     * @return mixed
     */
    public function handleEvent(object $event);

    public function boot();

    public function handle(ServerRequestInterface $request): ResponseInterface;

    /**
     * @return RuntimeConfiguration
     */
    public function getRuntimeConfiguration(): RuntimeConfiguration;
}