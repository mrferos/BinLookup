<?php
namespace BinLookupTest\Provider;

use BinLookup\Provider\NeutrinoApi;

class NeutrinoApiTest extends \PHPUnit_Framework_TestCase
{
    public function testSetHttpClient()
    {
        $client = $this->getMock('\Guzzle\Http\ClientInterface');
        $objectHash = spl_object_hash($client);

        $NeutrinoApi = new NeutrinoApi();
        $NeutrinoApi->setUserName('test');
        $NeutrinoApi->setPassCode('test');
        $NeutrinoApi->setHttpClient($client);

        $httpClient = $NeutrinoApi->getHttpClient();

        $this->assertInstanceOf('\Guzzle\Http\ClientInterface', $httpClient);
        $this->assertEquals($objectHash, spl_object_hash($httpClient));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetHttpClientInvalidType()
    {
        $NeutrinoApi = new NeutrinoApi();
        $NeutrinoApi->setUserName('test');
        $NeutrinoApi->setPassCode('test');
        $NeutrinoApi->setHttpClient(array());
    }

    public function testGetHttpClientWhenPropertyIsNull()
    {
        $NeutrinoApi = new NeutrinoApi();
        $NeutrinoApi->setUserName('test');
        $NeutrinoApi->setPassCode('test');
        $reflObject = new \ReflectionObject($NeutrinoApi);
        $httpProp = $reflObject->getProperty('httpClient');
        $httpProp->setAccessible(true);
        $httpProp->setValue($NeutrinoApi, null);

        $httpClient = $NeutrinoApi->getHttpClient();
        $this->assertInstanceOf('\Guzzle\Http\ClientInterface', $httpClient);
    }

    public function testSearchInvalidRequest()
    {
        $client = $this->getMock('\Guzzle\Http\ClientInterface');
        $client->expects($this->once())
            ->method('post')
            ->will($this->returnCallback(array($this, 'getErroredRequest')));

        $NeutrinoApi = new NeutrinoApi();
        $NeutrinoApi->setUserName('test');
        $NeutrinoApi->setPassCode('test');
        $NeutrinoApi->setHttpClient($client);
        $result = $NeutrinoApi->search(123);

        $this->assertFalse($result);
    }

    public function testSearchNonSuccessfulRequest()
    {
        $client = $this->getMock('\Guzzle\Http\ClientInterface');
        $client->expects($this->once())
            ->method('post')
            ->will($this->returnCallback(function() {
                $mock = $this->getMock('\Guzzle\Http\Message\RequestInterface');
                $mock->expects($this->once())
                    ->method('send')
                    ->will($this->returnCallback(array($this, 'getNotSuccessfulResponse')));

                return $mock;
            }));

        $NeutrinoApi = new NeutrinoApi();
        $NeutrinoApi->setUserName('test');
        $NeutrinoApi->setPassCode('test');
        $NeutrinoApi->setHttpClient($client);
        $result = $NeutrinoApi->search(123);

        $this->assertFalse($result);
    }

    public function testSearchValidRequestPartialReturn()
    {
        $client = $this->getMock('\Guzzle\Http\ClientInterface');
        $client->expects($this->once())
            ->method('post')
            ->will($this->returnCallback(array($this, 'getSuccessfulRequest')));

        $NeutrinoApi = new NeutrinoApi();
        $NeutrinoApi->setUserName('test');
        $NeutrinoApi->setPassCode('test');
        $NeutrinoApi->setHttpClient($client);
        $result = $NeutrinoApi->search(123);


        $this->assertInstanceOf('\BinLookup\Provider\Result', $result);
    }

    public function getSuccessfulRequest()
    {
        $request = $this->getMock('\Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())
            ->method('send')
            ->will($this->returnCallback(array($this, 'getPartialSuccessfulResponse')));

        return $request;
    }

    public function getNotSuccessfulResponse()
    {
        $response = $this->getMockBuilder('\Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(false));
        return $response;
    }


    public function getPartialSuccessfulResponse()
    {
        $response = $this->getMockBuilder('\Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(true));

        $response->expects($this->once())
            ->method('json')
            ->will($this->returnValue(array(
                'card_category' => 'VISA',
                'bin' => 4322
            )));

        return $response;
    }

    public function getErroredRequest()
    {
        $request = $this->getMock('\Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())
            ->method('send')
            ->will($this->returnCallback(array($this, 'throwClientException')));

        return $request;
    }

    public function throwClientException()
    {
        throw new \Exception('Invalid request');
    }
}