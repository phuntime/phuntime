<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;


use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class WrappedClosureHandlerTest extends TestCase
{

    public function testWrapperHandlesClosures()
    {
        $function = function (\Nyholm\Psr7\ServerRequest $request) {
            return new \Nyholm\Psr7\Response();
        };

        $wrappedHandler = new WrappedClosureHandler($function);

        $this->assertInstanceOf(ResponseInterface::class, $wrappedHandler->handle(new ServerRequest('POST', '/')));
    }
}