<?php
namespace BinLookupTest\Provider;

use BinLookup\Provider\ProviderAbstract;

class TestProviderAbstract extends ProviderAbstract {

    protected $_optionOne;

    /**
     * @param int $binNumber
     * @return mixed
     */
    public function search($binNumber)
    {
        // TODO: Implement search() method.
    }

    /**
     * @return mixed
     */
    public function getOptionOne()
    {
        return $this->_optionOne;
    }

    /**
     * @param mixed $optionOne
     */
    public function setOptionOne($optionOne)
    {
        $this->_optionOne = $optionOne;
    }



}