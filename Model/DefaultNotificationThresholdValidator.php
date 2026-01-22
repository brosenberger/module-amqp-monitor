<?php

namespace BroCode\AmqpMonitor\Model;

use BroCode\AmqpMonitor\Api\NotificationThresholdValidatorInterface;

class DefaultNotificationThresholdValidator implements NotificationThresholdValidatorInterface
{

    public function aboveThreshold($queueName, $oldInfo, $newInfo): bool
    {
        // TODO
        return true;
    }
}
