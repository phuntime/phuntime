<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
interface Psr7FunctionInterface extends FunctionInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}