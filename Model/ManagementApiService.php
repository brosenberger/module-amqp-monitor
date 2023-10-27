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

    protected $client = null;

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
        if ($this->client === null) {

            $baseUri = $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_ENDPOINT);
            if (empty($baseUri)) {
                return null;
            }

            $this->client = new \GuzzleHttp\Client([
                'base_uri' => $baseUri,
                'auth' => [
                    $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_USERNAME) ?? $this->amqpConfig->getValue(Config::USERNAME),
                    $this->encryptor->decrypt($this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_PASSWORD)) ?? $this->amqpConfig->getValue(Config::PASSWORD)
                ]
            ]);

        }
        return $this->client;
    }

    public function getAmqpConfiguration()
    {
        return [
            Config::HOST => $this->amqpConfig->getValue(Config::HOST),
            Config::PORT => $this->amqpConfig->getValue(Config::PORT),
            Config::USERNAME => $this->amqpConfig->getValue(Config::USERNAME),
            Config::PASSWORD => $this->amqpConfig->getValue(Config::PASSWORD) ? '********' : '',
            Config::VIRTUALHOST => $this->amqpConfig->getValue(Config::VIRTUALHOST),
            Config::SSL => $this->amqpConfig->getValue(Config::SSL),
            Config::SSL_OPTIONS => print_r($this->amqpConfig->getValue(Config::SSL_OPTIONS), true),
        ];
    }

    public function getManagementApiConfiguration()
    {
        $informationOverview = $this->getInformationOverview();

        return [
            'Management-Endpoint' => '<a href="' . str_replace('api/','', $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_ENDPOINT)) . '" target="_blank">' .  str_replace('api/','', $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_ENDPOINT)) . '</a>',
            'API Base-Endpoint' => $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_ENDPOINT),
            'API Username' => $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_USERNAME),
            'API Password' => $this->scopeConfig->getValue(Constants::CONFIG_GENERAL_API_PASSWORD) ? '********' : '',
        ];
    }

    public function getInformationOverview()
    {
        $client = $this->getClient();
        $response = $client->request('GET', 'overview/');
        $responseBody = $response->getBody();
        $responseJson = json_decode($responseBody, true);
        return $responseJson;
    }
}