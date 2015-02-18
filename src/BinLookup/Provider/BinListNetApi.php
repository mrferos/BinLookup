<?php
namespace BinLookup\Provider;

use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;

class BinListNetApi extends ProviderAbstract
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiUrl = 'http://www.binlist.net/json/';

    /**
     * @var array
     */
    protected $_dataMap = array(
        'bin' => 'bin',
        'brand' => 'brand',
        'sub_brand' => 'subBrand',
        'country_code' => 'countryCode',
        'country_name' => 'country',
        'bank' => 'bank',
        'card_type' => 'cardType',
        'card_category' => 'cardCategory',
    );

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (empty($this->httpClient)) {
            $this->setHttpClient(new Client($this->apiUrl));
        }

        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     */
    public function setHttpClient($httpClient)
    {
        if (!$httpClient instanceof ClientInterface) {
            throw new \InvalidArgumentException('$httpClient must be an instance of Client');
        }

        $this->httpClient = $httpClient;
    }

    /**
     * @param int $binNumber
     * @return Result|bool
     */
    public function search($binNumber)
    {
        $httpClient = $this->getHttpClient();
        try {
            $response = $httpClient->get($binNumber)->send();
            if ($response->isSuccessful()) {
                $data = $response->json();
                return $this->hydrate($data);
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }
}