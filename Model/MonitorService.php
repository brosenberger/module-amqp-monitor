<?php

namespace BroCode\AmqpMonitor\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Flag\FlagResource;
use Magento\Framework\FlagFactory;
use Magento\Framework\Serialize\Serializer\Json;

class MonitorService
{
    private const FLAG_CODE = 'amqp_monitor_notifications';

    private ManagementApiService $managementApiService;
    private FlagFactory $flagFactory;
    private FlagResource $flagResource;
    private Json $serializer;

    public function __construct(
        FlagFactory $flagFactory,
        FlagResource $flagResource,
        Json $serializer,
        ManagementApiService $managementApiService
    ) {
        $this->managementApiService = $managementApiService;
        $this->flagFactory = $flagFactory;
        $this->flagResource = $flagResource;
        $this->serializer = $serializer;
    }

    /**
     * Returns the stored queue informations, if none are available,
     * the current information is retrieved
     *
     * @return array
     * @throws LocalizedException
     */
    public function getStoredQueueInformations()
    {
        $values = $this->getFlagValue(self::FLAG_CODE);
        if (empty($values)) {
            $this->getCurrentQueueInformations();
        }
        return $values;
    }

    /**
     * Returns the current queue information and stores them
     * @return array
     * @throws LocalizedException
     */
    public function getCurrentQueueInformations()
    {
        $values = $this->retrieveInformations();
        $this->updateFlagValue(self::FLAG_CODE, $values);
        return $values;
    }

    /**
     * @return array
     */
    protected function retrieveInformations()
    {
        try {
            $queueInfos = $this->managementApiService->getQueueInfos();
            $queueInfos = array_column($queueInfos, null, 'name');
            return $queueInfos;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param string $code
     * @return mixed
     * @throws LocalizedException
     */
    protected function getFlagValue(string $code)
    {
        $flag = $this->flagFactory->create([
            'data' => [
                'flag_code' => $code
            ]
        ])->loadSelf();
        $flagData = $flag->getFlagData();
        return $this->serializer->unserialize($flagData??'{}');
    }

    /**
     * @param string $code
     * @param $value
     * @throws LocalizedException
     */
    protected function updateFlagValue(string $code, $value): void
    {
        $flag = $this->flagFactory->create([
            'data' => [
                'flag_code' => $code
            ]
        ])->loadSelf();

        $flag->setFlagData($this->serializer->serialize($value));
        $this->flagResource->save($flag);
    }
}
