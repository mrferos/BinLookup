<?php
namespace BinLookup\Provider;

interface ProviderInterface
{
    /**
     * @param array $options
     */
    public function __construct($options = array());

    /**
     * Configures the provider, sets options via setters
     *
     * @param $options
     * @return void
     */
    public function setOptions($options);

    /**
     * Return the name of the provider
     *
     * @return string
     */
    public function getName();

    /**
     * @param int $binNumber
     * @return mixed
     */
    public function search($binNumber);
}