<?php

declare(strict_types=1);

namespace Matt\Pokemon\Service;

use Magento\Framework\App\CacheInterface;
use Matt\Pokemon\Api\PokeApiServiceInterface;
use Matt\Pokemon\HttpRemote\Request\PokeRequest;
use Matt\Pokemon\HttpRemote\Request\PokeRequestFactory;
use Matt\Pokemon\Service\Gateway\Gateway;

/**
 * Class PokeApiService
 *
 * @package Matt\Pokemon\Service
 */
class PokeApiService implements PokeApiServiceInterface
{
    private CacheInterface $cache;
    private Gateway $gateway;
    private PokeRequestFactory $pokeRequestFactory;

    /**
     * @param Gateway $gateway
     * @param PokeRequestFactory $pokeRequestFactory
     */
    public function __construct(
        CacheInterface $cache,
        Gateway $gateway,
        PokeRequestFactory $pokeRequestFactory
    ) {
        $this->cache = $cache;
        $this->gateway = $gateway;
        $this->pokeRequestFactory = $pokeRequestFactory;
    }

    /**
     * @param string $pokemonName
     * @return array
     */
    public function getPokeData(string $pokemonName): array
    {
        $cacheData = $this->cache->load($pokemonName);

        if (!$cacheData) {
            /** @var PokeRequest $pokeRequest */
            $pokeRequest = $this->pokeRequestFactory->create();
            $pokeRequest->setFilter("pokemon-form");
            $pokeRequest->setValue($pokemonName);

            $response = $this->gateway->request($pokeRequest);

            if ($response->getMessage() == null) {
                $this->cache->save($response->toJson(), $pokemonName, [], 82000);

                return $response->toArray();
            }

            return [];
        }

        return json_decode($cacheData, true);
    }
}
