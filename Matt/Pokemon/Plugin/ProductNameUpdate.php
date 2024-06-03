<?php

declare(strict_types=1);

namespace Matt\Pokemon\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Controller\Product\View as ProductViewController;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Page\Config;
use Matt\Pokemon\Api\ConfigurationReaderInterface;
use Matt\Pokemon\Api\PokeApiServiceInterface;
use Matt\Pokemon\Setup\Patch\Data\AddPokemonNameAttribute;

class ProductNameUpdate
{
    private LayoutInterface $layout;
    Private Config $config;
    private ConfigurationReaderInterface $configurationReader;
    private CatalogSession $catalogSession;
    private PokeApiServiceInterface $pokeApiService;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param LayoutInterface $layout
     * @param Config $config
     * @param ConfigurationReaderInterface $configurationReader
     * @param CatalogSession $catalogSession
     * @param PokeApiServiceInterface $pokeApiService
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        LayoutInterface $layout,
        Config $config,
        ConfigurationReaderInterface $configurationReader,
        CatalogSession $catalogSession,
        PokeApiServiceInterface $pokeApiService,
        ProductRepositoryInterface $productRepository
    ) {
        $this->layout = $layout;
        $this->config = $config;
        $this->configurationReader = $configurationReader;
        $this->catalogSession = $catalogSession;
        $this->pokeApiService = $pokeApiService;
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductViewController $subject
     * @param ResultInterface $result
     * @return ResultInterface
     * @throws NoSuchEntityException
     */
    public function afterExecute(ProductViewController $subject, ResultInterface $result): ResultInterface
    {
        if ($this->configurationReader->isEnabled()) {
            $productId = $this->catalogSession->getData('last_viewed_product_id');
            $currentProduct = $this->productRepository->getById($productId);
            $pokemonName = $currentProduct
                ->getCustomAttribute(AddPokemonNameAttribute::ATTRIBUTE_CODE)
                ->getValue();

            $pokeData = $this->pokeApiService->getPokeData($pokemonName);

            if (!empty($pokeData)) {
                $this->setPokeData($pokeData);
            }
        }

        return $result;
    }

    /**
     * @param array $pokeData
     * @return void
     */
    private function setPokeData(array $pokeData): void
    {
        $pageTitle = $this->config->getTitle();
        $pageTitle->set(strtoupper($pokeData['name']));

        $pageTitleBlock = $this->layout->getBlock('page.main.title');
        if ($pageTitleBlock) {
            $pageTitleBlock->setPageTitle(strtoupper($pokeData['name']));
        }
    }
}
