<?php

declare(strict_types=1);

namespace Matt\Pokemon\Service\Gateway;

use Magento\Framework\HTTP\Client\Curl;
use Matt\Pokemon\Api\ConfigurationReaderInterface;
use Matt\Pokemon\HttpRemote\Request\PokeRequest;
use Matt\Pokemon\HttpRemote\Response\PokeResponse;
use Matt\Pokemon\HttpRemote\Response\PokeResponseFactory;
use Psr\Log\LoggerInterface;

class Gateway
{
    private ConfigurationReaderInterface $configurationReader;
    private Curl $curl;
    private LoggerInterface $logger;
    private PokeResponseFactory $pokeResponseFactory;

    /**
     * @param ConfigurationReaderInterface $configurationReader
     * @param Curl $curl
     * @param LoggerInterface $logger
     * @param PokeResponseFactory $pokeResponseFactory
     */
    public function __construct(
        ConfigurationReaderInterface $configurationReader,
        Curl $curl,
        LoggerInterface $logger,
        PokeResponseFactory $pokeResponseFactory
    ) {
        $this->configurationReader = $configurationReader;
        $this->curl = $curl;
        $this->logger = $logger;
        $this->pokeResponseFactory = $pokeResponseFactory;
    }

    /**
     * @param PokeRequest $request
     * @return PokeResponse
     */
    public function request(PokeRequest $request): PokeResponse
    {
        $configApiUrl = $this->configurationReader->getApiUrl();
        $url = $configApiUrl . $request->getFilter() . DIRECTORY_SEPARATOR . $request->getValue();

        if (!empty($request->getLimit())) {
            $url .= '?limit=' . $request->getLimit();
        }

        /** @var PokeResponse $pokeResponse */
        $pokeResponse = $this->pokeResponseFactory->create();

        try {
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->get($url);
            $pokeResponse = $this->buildResponseObject($pokeResponse, $this->curl->getBody());
        } catch (\Exception $e) {
            $this->logger->error('PokeApi Error: ' . $e->getMessage());
            $pokeResponse->setMessage($e->getMessage());
        }

        return $pokeResponse;
    }

    /**
     * @param string $responseData
     * @param PokeResponse $pokeResponse
     * @return PokeResponse
     */
    private function buildResponseObject(PokeResponse $pokeResponse, string $responseData): PokeResponse
    {
        $dataArray = json_decode($responseData, true);
        $pokeResponse->setName($dataArray['name']);
        $pokeResponse->setImgUrl($dataArray['sprites']['front_default']);

        return $pokeResponse;
    }
}
