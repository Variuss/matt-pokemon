<?php

declare(strict_types=1);

namespace Matt\Pokemon\Api;

interface PokeApiServiceInterface
{
    /**
     * @param string $pokemonName
     * @return array
     */
    public function getPokeData(string $pokemonName): array;
}
