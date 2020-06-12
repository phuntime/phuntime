<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpFoundationFunction implements HttpFoundationFunctionInterface
{

    public function handle(Request $request): Response
    {
        // TODO: Implement handle() method.
    }
}