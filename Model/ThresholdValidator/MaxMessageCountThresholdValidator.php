<?php

namespace BroCode\AmqpMonitor\Model\ThresholdValidator;

use BroCode\AmqpMonitor\Api\Constants;
use BroCode\AmqpMonitor\Api\NotificationThresholdValidatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class MaxMessageCountThresholdValidator implements NotificationThresholdValidatorInterface
{
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function aboveThreshold($queueName, $oldInfo, $newInfo): bool
    {
        $alertCount = (int) $this->scopeConfig->getValue(Constants::CONFIG_NOTIFICATIONS_THRESHOLD_ALERTMESSAGECOUNT);
        return $newInfo['messages_ready'] > $alertCount;
        // TODO: Implement aboveThreshold() method.
    }
}
