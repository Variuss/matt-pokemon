<?php

declare(strict_types=1);

namespace Matt\Pokemon\Model\Resolver;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Matt\Pokemon\Api\PokeApiServiceInterface;
use Matt\Pokemon\Setup\Patch\Data\AddPokemonNameAttribute;

class PokeDataResolver implements ResolverInterface
{
    private PokeApiServiceInterface $pokeApiService;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param PokeApiServiceInterface $pokeApiService
     */
    public function __construct(
        PokeApiServiceInterface $pokeApiService,
        ProductRepositoryInterface $productRepository
    ) {
        $this->pokeApiService = $pokeApiService;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     *
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array
    {
        if (isset($args['product_id'])) {
            $productId = (int)$args['product_id'];

            try {
                $product = $this->productRepository->getById($productId);
                $pokemonName = $product
                    ->getCustomAttribute(AddPokemonNameAttribute::ATTRIBUTE_CODE)
                    ->getValue();
                return $this->pokeApiService->getPokeData($pokemonName);
            } catch (NoSuchEntityException $exception) {
                throw new GraphQlNoSuchEntityException(__($exception->getMessage()), $exception);
            }
        } else {
            throw new GraphQlInputException(
                __("'rma_request_id' input argument is required.")
            );
        }
    }
}
