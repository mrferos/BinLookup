<?php
namespace BinLookup;

use BinLookup\Provider\ProviderInterface;
use BinLookup\Provider\Result;

class BinLookup
{
    /**
     * @var ProviderInterface[]
     */
    protected $_providers = array();

    /**
     * @param $bin
     * @return Result|false
     * @throws \InvalidArgumentException
     */
    public function search($bin)
    {
        if (!is_int($bin)) {
            throw new \InvalidArgumentException('$bin must be an int');
        }

        $providers = $this->getProviders();
        if (empty($providers)) {
            throw new \RuntimeException('No registered providers');
        }

        foreach ($providers as $provider) {
            $result = $provider->search($bin);
            if ($result instanceof Result) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param ProviderInterface|string $provider
     * @param array|null $config
     * @returns void
     */
    public function addProvider($provider, $config = null)
    {
        if (is_string($provider)) {
            $provider = $this->loadProvider($provider, $config);
        }

        if (!is_object($provider) || !is_subclass_of($provider, '\BinLookup\Provider\ProviderInterface')) {
            throw new \InvalidArgumentException('$provider must be an instance of ProviderInterface');
        }

        $this->_providers[$provider->getName()] = $provider;
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProviders()
    {
        return $this->_providers;
    }

    /**
     * @param string $provider
     * @param array|null $config
     * @returns ProviderInterface
     */
    public function loadProvider($provider, $config = null)
    {
        if (!class_exists($provider)) {
            throw new \InvalidArgumentException('$provider is not a valid class');
        }

        if (!is_subclass_of($provider, '\BinLookup\Provider\ProviderInterface')) {
            throw new \InvalidArgumentException('$provider must be an instance of ProviderInterface');
        }

        return new $provider($config);
    }
}