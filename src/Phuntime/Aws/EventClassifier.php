<?php
declare(strict_types=1);

namespace Phuntime\Aws;


/**
 * Detects which AWS Event we have to deal with.
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class EventClassifier
{

    /**
     * @param array $eventBody
     * @return bool
     */
    public function isApiGatewayV1ProxyEvent(array $eventBody): bool
    {
        if (!isset($eventBody['httpMethod'])) {
            return false;
        }

        if (!isset($eventBody['multiValueQueryStringParameters'])) {
            return false;
        }

        if(!is_array($eventBody['multiValueQueryStringParameters'])) {
            return false;
        }

        if(!isset($eventBody['version'])) {
            return false;
        }

        if($eventBody['version'] !== '1.0') {
            return false;
        }

        return true;
    }

    public function isApiGatewayV2ProxyEvent(array $eventBody): bool
    {
        if(isset($eventBody['version']) && $eventBody['version'] !== '2.0') {
            return false;
        }

        if(!isset($eventBody['requestContext'])) {
            return false;
        }

        return true;
    }
}