<?php

namespace BroCode\AmqpMonitor\Model;

class MonitorService
{
    private ManagementApiService $managementApiService;

    public function __construct(
        ManagementApiService $managementApiService
    ) {
        $this->managementApiService = $managementApiService;
    }

    public function getStoredQueueInformations()
    {
        // TODO check stored values, otherwise retrieve from directly from queue
        return $this->retrieveInformations();
    }

    public function getCurrentQueueInformations()
    {
        // TODO store new values and return them
        return $this->retrieveInformations();
    }

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
}
