<?php

namespace BroCode\AmqpMonitor\Model\ThresholdValidator;

use BroCode\AmqpMonitor\Api\NotificationThresholdValidatorInterface;

class CompositeThresholdValidator implements NotificationThresholdValidatorInterface
{

    /**
     * @var \BroCode\AmqpMonitor\Api\NotificationThresholdValidatorInterface[]
     */
    private array $validators;

    public function __construct(
        array $validators = []
    ) {
        $this->validators = $validators;
    }

    public function aboveThreshold($queueName, $oldInfo, $newInfo): bool
    {
        return array_reduce(
            $this->validators,
            function($aboveThreshold, $validator) use ($queueName, $oldInfo, $newInfo) {
                return $aboveThreshold | $validator->aboveThreshold($queueName, $oldInfo, $newInfo);
            },
            false
        );
    }
}
