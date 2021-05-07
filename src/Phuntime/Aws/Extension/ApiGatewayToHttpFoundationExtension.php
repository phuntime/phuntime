<?php
declare(strict_types=1);

namespace Phuntime\Aws\Extension;

use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Core\Contract\EventInterface;
use Phuntime\Core\EventTransformer\SyncEventTransformerInterface;

/**
 * Converts API Gateway Events to symfony/http-foundation Request/Response objects and vice-versa.
 * @package Phuntime\Aws\Extension
 * @license MIT
 */
class ApiGatewayToHttpFoundationExtension implements SyncEventTransformerInterface
{



    public function transformFromRuntime(EventInterface $object): object
    {
        // TODO: Implement transformFromRuntime() method.
    }

    public function transformToRuntime(object $event): EventInterface
    {
        // TODO: Implement transformToRuntime() method.
    }

    public function supports(object $event): bool
    {
        return $event instanceof ApiGatewayProxyEvent;
    }
}