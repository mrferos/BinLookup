<?php
namespace BinLookupTest\Provider;

class ProviderAbstractTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        require_once __DIR__ . '/../../_fixtures/Provider/TestProviderAbstract.php';
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetOptionsOnInvalidOption()
    {
        $providerAbstract = new TestProviderAbstract();
        $providerAbstract->setOptions(array(
            'non_existant_option' => 'foo'
        ));
    }

    public function testSetOptionsWithValidUnderScoreOption()
    {
        $providerAbstract = new TestProviderAbstract();
        $providerAbstract->setOptions(array(
            'option_one' => 'foo'
        ));

        $this->assertEquals('foo', $providerAbstract->getOptionOne());
    }

    public function testSetOptionsWithValidCamelCaseOption()
    {
        $providerAbstract = new TestProviderAbstract();
        $providerAbstract->setOptions(array(
            'optionOne' => 'foo'
        ));

        $this->assertEquals('foo', $providerAbstract->getOptionOne());
    }

    public function testSetOptionsInConstructor()
    {
        $providerAbstract = new TestProviderAbstract(array(
            'optionOne' => 'foo'
        ));

        $this->assertEquals('foo', $providerAbstract->getOptionOne());
    }
}