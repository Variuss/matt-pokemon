<?php

declare(strict_types=1);

namespace Matt\Pokemon\Test\Unit\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Matt\Pokemon\Service\ConfigurationReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigurationReaderTest extends TestCase
{
    private ScopeConfigInterface|MockObject $scopeConfigMock;
    private StoreManagerInterface|MockObject $storeManagerMock;
    private ConfigurationReader $testedObject;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->testedObject = new ConfigurationReader(
            $this->scopeConfigMock,
            $this->storeManagerMock
        );
    }

    public function testIsEnabled()
    {
        $xmlPath = 'matt_pokemon/general/enabled';
        $scope = 'store';
        $storeId = 1;

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with($xmlPath, $scope, $storeId)
            ->willReturn(true);
    }

    public function testGetApiUrl()
    {
        $xmlPath = 'matt_pokemon/general/api_url';
        $scope = 'store';
        $storeId = 1;
        $apiUrl = 'https://pokeapi.co/api/v2/';

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with($xmlPath, $scope, $storeId)
            ->willReturn($apiUrl);
    }
}
