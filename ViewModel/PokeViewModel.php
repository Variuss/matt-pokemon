<?php

declare(strict_types=1);

namespace Matt\Pokemon\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Matt\Pokemon\Api\ConfigurationReaderInterface;
use Matt\Pokemon\Api\PokeApiServiceInterface;
use Matt\Pokemon\Setup\Patch\Data\AddPokemonNameAttribute;

class PokeViewModel implements ArgumentInterface
{
    private ConfigurationReaderInterface $configurationReader;
    private PokeApiServiceInterface $pokeApiService;

    /**
     * @param ConfigurationReaderInterface $configurationReader
     * @param PokeApiServiceInterface $pokeApiService
     */
    public function __construct(
        ConfigurationReaderInterface $configurationReader,
        PokeApiServiceInterface $pokeApiService
    ) {
        $this->configurationReader = $configurationReader;
        $this->pokeApiService = $pokeApiService;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getPokeData(Product $product): array
    {
        if ($this->configurationReader->isEnabled()) {
            $pokemonName = $product->getAttributeText(AddPokemonNameAttribute::ATTRIBUTE_CODE);

            return $this->pokeApiService->getPokeData($pokemonName);
        }

        return [];
    }
}
