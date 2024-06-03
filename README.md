Module instruction

This module provides example implementation of PokeApi: https://pokeapi.co/
Currently there is name and imgUrl(front_default) available.

## Installation

COMPOSER INSTALLATION
Run composer command:
$> composer require matt/module-matt-pokemon

MANUAL INSTALLATION
Extract files from an archive

deploy files into Magento2 folder app/code/Matt/Pokemon

## Explanations

Observer is responsible for clear cache when product is updated.
Plugin is responsible for update the product name on detail page.
In Service directory we have:
- Gateway for connection and returning appropriate response
- ConfigurationReader for config reading
- PokeApiService for providing data from api

Also in HttpRemote there are classes responsible for request and response objects
to keep everything object oriented.

In setup there is only installation of our product attribute.
In ViewModel we are retrieving data for listing template.

## Bonus 1 Test

Only one basic sample testing class for ConfigurationReader.

## Bonus 2 GraphQl

There is one sample graphql query to get data for PWA projects when we don't use
basic Magento front templates.

```graphql endpoint
{
  getPokeData(product_id: 1) {
        name
        img_url
        message
  }
}
```
