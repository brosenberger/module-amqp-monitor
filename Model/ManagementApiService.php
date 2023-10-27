<?php

namespace BroCode\AmqpMonitor\Model;

use BroCode\AmqpMonitor\Api\Constants;
use Magento\Framework\Amqp\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\Encryptor;

class ManagementApiService
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var Config
     */
    protected $amqpConfig;
    /**
     * @var Encryptor
     */
    protected $encryptor;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Encryptor $encryptor
     * @param Config $amqpConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Encryptor $encryptor,
        Config $amqpConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->amqpConfig = $amqpConfig;
        $this->encryptor = $encryptor;
    }

    public function getQueueInfos()
    {
        $client = $this->getClient();
        if ($client === null) {
            return [];
        }
        $response = $client->request('GET', 'queues/' .urlencode($this->amqpConfig->getValue(Config::VIRTUALHOST)).'/');
        $responseBody = $response->getBody();
        $responseJson = json_decode($responseBody, true);
        return $responseJson;
    }

    protected function getClient()
    {
        $baseUri = $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_ENDPOINT);
        if (empty($baseUri)) {
            return null;
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => $baseUri,
            'auth' => [
                $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_USERNAME) ?? $this->amqpConfig->getValue(Config::USERNAME),
                $this->encryptor->decrypt($this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_PASSWORD)) ?? $this->amqpConfig->getValue(Config::PASSWORD)
            ]
        ]);
        return $client;
    }
}