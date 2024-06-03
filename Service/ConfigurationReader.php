<?php

declare(strict_types=1);

namespace Matt\Pokemon\Service;

use Magento\Framework\Exception\NoSuchEntityException;
use Matt\Pokemon\Api\ConfigurationReaderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigurationReader implements ConfigurationReaderInterface
{
    private const XML_PATH_MODULE_ENABLED = 'matt_pokemon/general/enabled';
    private const XML_PATH_API_URL = 'matt_pokemon/general/api_url';
    private ScopeConfigInterface $scopeConfig;
    private StoreManagerInterface $storeManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_MODULE_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getApiUrl(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_API_URL,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }
}
