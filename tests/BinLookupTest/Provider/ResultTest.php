<?php
namespace BinLookupTest\Provider;

use BinLookup\Provider\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getResultData
     */
    public function testGetProperty($property, $value)
    {
        $result = new Result(array($property => $value));
        $this->assertEquals(call_user_func(array($result, 'get' . ucfirst($property))), $value);
    }

    public function getResultData()
    {
        return array(
            array('bin' , 123),
            array('brand' , 'VISA'),
            array('countryCode' , 'US'),
            array('country' , 'United States'),
            array('bank' , 'Bank of America'),
            array('cardType' , 'Debit'),
            array('latitude' , 122),
            array('longitude' , 124),
            array('cardSubBrand' , 'Sub Brand'),
            array('cardCategory' , 'Card Category'),
        );
    }
}