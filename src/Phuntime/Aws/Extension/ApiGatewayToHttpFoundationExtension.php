<?php
declare(strict_types=1);

namespace Phuntime\Aws\Extension;

use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayV2ProxyEvent;
use Phuntime\Core\Contract\EventInterface;
use Phuntime\Core\EventTransformer\SyncEventTransformerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Converts API Gateway Events to symfony/http-foundation Request/Response objects and vice-versa.
 * @package Phuntime\Aws\Extension
 * @license MIT
 */
class ApiGatewayToHttpFoundationExtension implements SyncEventTransformerInterface
{

    /**
     * @param EventInterface|ApiGatewayProxyEvent|ApiGatewayV2ProxyEvent $object
     * @return Request
     */
    public function transformFromRuntime(EventInterface $object): object
    {
        if(!($object instanceof ApiGatewayProxyEvent)) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects instance of %s or %s, %s given',
                __CLASS__,
                ApiGatewayProxyEvent::class,
                ApiGatewayV2ProxyEvent::class,
                get_class($object)
            ));
        }

        return Request::create(
            $object->getPath(),
            $object->getHttpMethod()
        );
    }

    public function transformToRuntime(object $event): EventInterface
    {
        // TODO: Implement transformToRuntime() method.
    }

    public function supports(object $from, string $to): bool
    {
        return $from instanceof ApiGatewayProxyEvent && $to === Request::class;
    }
}