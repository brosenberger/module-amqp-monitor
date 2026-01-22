<?php

namespace BroCode\AmqpMonitor\Api;

interface NotificationPublisherInterface
{
    public function publish($queueName, $oldInformation, $newInformation);
}
