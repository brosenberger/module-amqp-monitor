<?php

namespace BroCode\AmqpMonitor\Cron;

use BroCode\AmqpMonitor\Api\Constants;
use BroCode\AmqpMonitor\Api\NotificationPublisherInterface;
use BroCode\AmqpMonitor\Api\NotificationThresholdValidatorInterface;
use BroCode\AmqpMonitor\Model\MonitorService;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckQueueMessageCountCron
{
    private MonitorService $monitorService;
    private ScopeConfigInterface $scopeConfig;
    private NotificationPublisherInterface $notificationPublisher;
    private NotificationThresholdValidatorInterface $notificationThresholdValidator;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param MonitorService $monitorService
     * @param NotificationPublisherInterface $notificationPublisher
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        MonitorService $monitorService,
        NotificationThresholdValidatorInterface $notificationThresholdValidator,
        NotificationPublisherInterface $notificationPublisher
    ) {
        $this->monitorService = $monitorService;
        $this->scopeConfig = $scopeConfig;
        $this->notificationPublisher = $notificationPublisher;
        $this->notificationThresholdValidator = $notificationThresholdValidator;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(Constants::CONFIG_NOTIFICATIONS_ENABLED) == false) {
            return;
        }
        $queueInfos = $this->monitorService->getStoredQueueCounts();
        foreach ($queueInfos as $queueName => $queueInfo) {
            $newQueueInfo = $this->monitorService->updateQueueInfo($queueName);
            if ($this->notificationThresholdValidator->aboveThreshold($queueName, $queueInfo, $newQueueInfo)) {
                $this->notificationPublisher->publish($queueName, $queueInfo, $newQueueInfo);
            }
        }
    }
}
