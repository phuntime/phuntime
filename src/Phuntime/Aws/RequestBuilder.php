<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Nyholm\Psr7\Request;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class RequestBuilder
{

    /**
     * @param array $apiGatewayEvent
     * @return ServerRequestInterface
     */
    public static function buildPsr7Request(array $apiGatewayEvent): ServerRequestInterface
    {
        $request = new ServerRequest();
//        $request->with
    }
}