<?php

namespace BroCode\AmqpMonitor\Block\Adminhtml;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Amqp\Config;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class QueueListing extends Template
{
    protected $_template = 'BroCode_AmqpMonitor::queue_listing.phtml';
    /**
     * @var \BroCode\AmqpMonitor\Model\ManagementApiService
     */
    protected $managementApiService;

    /**
     * @param Context $context
     * @param Config $amqpConfig
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
       \BroCode\AmqpMonitor\Model\ManagementApiService $managementApiService,
        array                                   $data = [],
        ?JsonHelper                             $jsonHelper = null,
        ?DirectoryHelper                        $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->managementApiService = $managementApiService;
    }

    public function getQueues() {
        $queues = $this->managementApiService->getQueueInfos();
        return $queues;
    }
}