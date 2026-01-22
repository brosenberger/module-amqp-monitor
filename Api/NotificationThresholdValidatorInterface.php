<?php

namespace BroCode\AmqpMonitor\Api;

interface NotificationThresholdValidatorInterface
{
    public function aboveThreshold($queueName, $oldInfo, $newInfo): bool;
}
