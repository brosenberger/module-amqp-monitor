<?php
namespace BroCode\AmqpMonitor\Controller\Adminhtml\Monitor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ACTION_RESOURCE = 'BroCode_AmqpMonitor::monitor';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        PageFactory $resultPageFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('BroCode_AmqpMonitor::monitor');
        $resultPage->getConfig()->getTitle()->prepend(__('Amqp Monitor'));
        $resultPage->addBreadcrumb(__('BroCode Amqp Monitor'), __('Monitor'));
        return $resultPage;
    }
}