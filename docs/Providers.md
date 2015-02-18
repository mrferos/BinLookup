Providers
=========

Providers are different source of BIN data. They all implement the ProviderInterface and thus expose a command API. 


## List of Providers

* Binlist
* Neutrino
    
## Configuration the Providers

### Neutrino
Register here for their API key: https://www.neutrinoapi.com/signup/
Instantiate the Provider and supply the apiKey and UserID
```php
$binListNetApi = new \BinLookup\Provider\NeutrinoApi();
$binListNetApi->setUserName('foo');
$binListNetApi->setPassCode('rawr');
``