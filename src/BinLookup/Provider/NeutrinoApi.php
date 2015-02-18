<?php
namespace BinLookup\Provider;

use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;

class NeutrinoApi extends ProviderAbstract
{
    /**
     * @var ClientInterface
     */
    protected $_httpClient;
    /**
     * @var string
     */
    protected $_apiUrl = 'https://neutrinoapi.com/bin-lookup';
    /**
     * @var string
     */
    protected $_userName;
    /**
     * @var string
     */
    protected $_passCode;

    /**
     * @var array
     */
    protected $_dataMap = array(
        'card-brand' => 'brand',
        'country-code' => 'countryCode',
        'country' => 'country',
        'issuer' => 'bank',
        'card-type' => 'cardType',
        'card-category' => 'cardCategory',
    );

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (empty($this->_httpClient)) {
            $this->setHttpClient(new Client($this->_apiUrl));
        }

        return $this->_httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     */
    public function setHttpClient($httpClient)
    {
        if (!$httpClient instanceof ClientInterface) {
            throw new \InvalidArgumentException('$httpClient must be an instance of Client');
        }

        $this->_httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->_userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->_userName = $userName;
    }

    /**
     * @return string
     */
    public function getPassCode()
    {
        return $this->_passCode;
    }

    /**
     * @param string $passCode
     */
    public function setPassCode($passCode)
    {
        $this->_passCode = $passCode;
    }

    /**
     * @param int $binNumber
     * @return Result|bool
     */
    public function search($binNumber)
    {
        $httpClient = $this->getHttpClient();

        $username = $this->getUserName();
        $apiKey   = $this->getPassCode();

        if (empty($username) || empty($apiKey)) {
            throw new \RuntimeException('Please provide a username and api key');
        }

        try {
            $response = $httpClient
                            ->post($this->_apiUrl, null, array(
                                'user-id'       => $username,
                                'api-key'    => $apiKey,
                                'bin-number' => $binNumber
                            ))
                            ->send();

            if ($response->isSuccessful()) {
                $data = $response->json();
                return $this->_hydrate(array_merge(
                    $data,
                    array('bin' => $binNumber)
                ));
            }
        }catch (\Exception $e) {
            return false;
        }

        return false;
    }
}