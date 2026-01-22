<?php

namespace BroCode\AmqpMonitor\Model\Notifications;

use BroCode\AmqpMonitor\Api\Constants;
use BroCode\AmqpMonitor\Api\NotificationPublisherInterface;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;

class EmailNotificationPublisher implements NotificationPublisherInterface
{

    private ScopeConfigInterface $scopeConfig;
    private TransportBuilder $transportBuilder;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
    }

    public function publish($queueName, $oldInformation, $newInformation)
    {
        if ($this->scopeConfig->getValue(Constants::CONFIG_NOTIFICATIONS_EMAIL_ENABLED) == false) {
            return;
        }

        $recipients = array_filter(array_unique(array_map('trim', explode(',', $this->scopeConfig->getValue(Constants::CONFIG_NOTIFICATIONS_EMAIL_RECIPIENTS) ?? ''))));
        if (empty($recipients)) {
            return;
        }

        $builder = $this->transportBuilder
            ->setTemplateIdentifier('amqp_monitor_notification')
            ->setTemplateOptions([
                'area' => FrontNameResolver::AREA_CODE,
                'store' => Store::DEFAULT_STORE_ID
            ])
            ->setFromByScope('general', Store::DEFAULT_STORE_ID)
            ->setTemplateVars([
                'queueName' => $queueName,
                'queueInformation' => json_encode($newInformation, JSON_PRETTY_PRINT)
            ]);

        foreach ($recipients as $email) {
            $builder->addTo($email);
        }

        $transport = $builder->getTransport();
        $transport->sendMessage();
    }
}
