<?php

namespace BroCode\AmqpMonitor\Model\ThresholdValidator;

use BroCode\AmqpMonitor\Api\Constants;
use BroCode\AmqpMonitor\Api\NotificationThresholdValidatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class MessageGrowthThresholdValidator implements NotificationThresholdValidatorInterface
{
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function aboveThreshold($queueName, $oldInfo, $newInfo): bool
    {
        $minMessages = (int) $this->scopeConfig->getValue(Constants::CONFIG_NOTIFICATIONS_THRESHOLD_MINMESSAGECOUNT);
        $growthPercentage = (float) $this->scopeConfig->getValue(Constants::CONFIG_NOTIFICATIONS_THRESHOLD_MINGROWTHPERCENTAGE);
        return $newInfo['messages_ready'] > $minMessages
            && $this->aboveGrowthRate($growthPercentage, $oldInfo['messages_ready'], $newInfo['messages_ready']);
    }

    protected function aboveGrowthRate($rate, $oldValue, $newValue): bool
    {
        if ($newValue < $oldValue) {
            // if fewer message return false
            return false;
        }

        // calcuclate grow percantage and check if more than the specified rate
        return $rate <= ((float)(($newValue - $oldValue)*100)/(float)$oldValue);
    }
}
