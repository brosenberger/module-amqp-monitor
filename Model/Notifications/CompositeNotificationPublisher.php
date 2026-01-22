<?php

namespace BroCode\AmqpMonitor\Model\Notifications;

use BroCode\AmqpMonitor\Api\NotificationPublisherInterface;

class CompositeNotificationPublisher implements NotificationPublisherInterface
{

    /** @var NotificationPublisherInterface[] */
    private ?array $publishers;

    public function __construct(
        ?array $publishers = []
    ) {
        $this->publishers = $publishers;
    }

    public function publish($queueName, $oldInformation, $newInformation)
    {
        foreach ($this->publishers as $publisher) {
            $publisher->publish($queueName, $oldInformation, $newInformation);
        }
    }
}
