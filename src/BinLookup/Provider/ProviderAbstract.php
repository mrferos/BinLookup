<?php
namespace BinLookup\Provider;

abstract class ProviderAbstract implements ProviderInterface
{
    public function __construct($options = array())
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions($options)
    {
        foreach ($options as $option => $value) {
            $optionName = $option;
            if (strstr($option, '_')) {
                $option = preg_replace_callback('/_([a-z])/', function($c) {
                    return strtoupper($c[1]);
                }, $option);
            }

            $method = 'set' . ucfirst($option);
            if (!method_exists($this, $method)) {
                            throw new \InvalidArgumentException('Option ' . $optionName . ' is not accepted');
            }

            call_user_func(array($this, $method), $value);
        }
    }

    /**
     * Return the name of the provider
     *
     * @return string
     */
    public function getName()
    {
        return get_called_class();
    }

    protected function _hydrate(array $dataToBeMapped)
    {
        if (!property_exists($this, '_dataMap')) {
            throw new \RuntimeException('The _dataMap property does not exists');
        }

        $dataMap = $this->_dataMap;
        $data = array();
        foreach ($dataMap as $key => $mappedKey) {
            if (array_key_exists($key, $dataToBeMapped)) {
                $data[$mappedKey] = $dataToBeMapped[$key];
            }
        }

        return new Result($data);
    }

}