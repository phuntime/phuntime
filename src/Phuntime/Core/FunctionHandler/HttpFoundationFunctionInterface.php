<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HttpFoundationFunctionInterface extends FunctionInterface
{

    public function handle(Request $request): Response;
}