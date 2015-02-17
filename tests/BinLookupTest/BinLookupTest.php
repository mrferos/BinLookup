<?php
namespace BinLookupTest;

use BinLookup\BinLookup;
use BinLookup\Provider\ProviderInterface;

class BinLookupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testSearchNoProviders()
    {
        $binlook = new BinLookup();
        $binlook->search(123);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSearchNonInt()
    {
        $binlook = new BinLookup();
        $binlook->search(array());
    }

    public function testSearchWithResult()
    {
        $providerMock = $this->getMock('\BinLookup\Provider\ProviderInterface');
        $providerMock->expects($this->once())
                        ->method('getName')
                        ->will($this->returnValue('test_proivider'));

        $providerMock->expects($this->once())
                        ->method('search')
                        ->will($this->returnCallback(function() {
                            return $this->getMockBuilder('\BinLookup\Provider\Result')
                                                ->setConstructorArgs(array(
                                                    array('brand' => 'MASTERCARD')
                                                ))
                                                ->getMock();


                        }));

        $binlook = new BinLookup();
        $binlook->addProvider($providerMock);
        $result = $binlook->search(123);

        $this->assertInstanceOf('\BinLookup\Provider\Result', $result);
    }

    public function testSearchWithNoResult()
    {
        $providerMock = $this->getMock('\BinLookup\Provider\ProviderInterface');
        $providerMock->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test_proivider'));

        $providerMock->expects($this->once())
            ->method('search')
            ->will($this->returnValue(false));

        $binlook = new BinLookup();
        $binlook->addProvider($providerMock);
        $result = $binlook->search(123);

        $this->assertFalse($result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetProviderInvalidDataType()
    {
        $binlook = new BinLookup();
        $binlook->addProvider(array());
    }

    public function testGetProviders()
    {
        $binlook = new BinLookup();
        $this->assertInternalType('array', $binlook->getProviders());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidString()
    {
        $binlook = new BinLookup();
        $binlook->addProvider('foo');
    }

    public function testSetValidProviderObject()
    {
        $provider = $this->getMock('\BinLookup\Provider\ProviderInterface');
        $provider->expects($this->once())
                    ->method('getName')
                        ->will($this->returnValue('TEST_PROVIDER'));

        $binlook = new BinLookup();
        $binlook->addProvider($provider);

        $reflObject = new \ReflectionObject($binlook);
        $providerProperty = $reflObject->getProperty('_providers');
        $providerProperty->setAccessible(true);
        $providers = $providerProperty->getValue($binlook);

        $this->assertArrayHasKey('TEST_PROVIDER', $providers);
        $this->assertInstanceOf('\BinLookup\Provider\ProviderInterface', $providers['TEST_PROVIDER']);
    }

    public function testSetValidProviderString()
    {
        require_once __DIR__ . '/../_fixtures/Provider/TestProvider.php';
        $binlook = new BinLookup();
        $binlook->addProvider('\BinLookupTest\Provider\TestProvider');

        $reflObject = new \ReflectionObject($binlook);
        $providerProperty = $reflObject->getProperty('_providers');
        $providerProperty->setAccessible(true);
        $providers = $providerProperty->getValue($binlook);

        $this->assertArrayHasKey('BinLookupTest\Provider\TestProvider', $providers);
        $this->assertInstanceOf('\BinLookupTest\Provider\TestProvider', $providers['BinLookupTest\Provider\TestProvider']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetValidClassButNotImplementingInterface()
    {
        require_once __DIR__ . '/../_fixtures/Provider/TestProviderNotImplementingInterface.php';
        $binlook = new BinLookup();
        $binlook->addProvider('\BinLookupTest\Provider\TestProviderNotImplementingInterface');
    }
}