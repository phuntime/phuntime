<?php
declare(strict_types=1);

namespace Phuntime\Aws;


/**
 * Detects which AWS Event we have to deal with.
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license
 */
class EventClassifier
{

    /**
     * @param array $eventBody
     * @return bool
     */
    public function isApiGatewayProxyEvent(array $eventBody): bool
    {
        if (!isset($eventBody['httpMethod'])) {
            return false;
        }

        if (!isset($eventBody['multiValueQueryStringParameters'])) {
            return false;
        }

        return true;
    }
}