<?php

namespace BroCode\AmqpMonitor\Model\Notifications;

use BroCode\AmqpMonitor\Api\NotificationPublisherInterface;

class EmailNotificationPublisher implements NotificationPublisherInterface
{

    public function publish($queueName, $oldInformation, $newInformation)
    {
        // TODO: Implement publish() method.
    }
}
