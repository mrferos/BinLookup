<?php
namespace BinLookup\Provider;

class Result
{
    protected $_bin;
    protected $_brand;
    protected $_countryCode;
    protected $_country;
    protected $_bank;
    protected $_cardType;
    protected $_latitude;
    protected $_longitude;
    protected $_cardSubBrand;
    protected $_cardCategory;

    /**
     * Set all the data for the result
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $property = '_' . $key;
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getBank()
    {
        return $this->_bank;
    }

    /**
     * @return mixed
     */
    public function getBin()
    {
        return $this->_bin;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->_brand;
    }

    /**
     * @return mixed
     */
    public function getCardCategory()
    {
        return $this->_cardCategory;
    }

    /**
     * @return mixed
     */
    public function getCardSubBrand()
    {
        return $this->_cardSubBrand;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->_cardType;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->_countryCode;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->_latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->_longitude;
    }
}