<?php

declare(strict_types=1);

namespace Matt\Pokemon\Observer;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Matt\Pokemon\Api\ConfigurationReaderInterface;
use Matt\Pokemon\Setup\Patch\Data\AddPokemonNameAttribute;

class ProductAfterSave implements ObserverInterface
{
    private CacheInterface $cache;
    private ConfigurationReaderInterface $configurationReader;

    /**
     * @param CacheInterface $cache
     * @param ConfigurationReaderInterface $configurationReader
     */
    public function __construct(
        CacheInterface $cache,
        ConfigurationReaderInterface $configurationReader
    ) {
        $this->cache = $cache;
        $this->configurationReader = $configurationReader;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if ($this->configurationReader->isEnabled()) {
            $product = $observer->getProduct();

            $productPokemonName = $product->getAttributeText(AddPokemonNameAttribute::ATTRIBUTE_CODE);

            if ($productPokemonName) {
                $this->cache->remove($productPokemonName);
            }
        }
    }
}
