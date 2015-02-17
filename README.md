![Travis-CI](https://travis-ci.org/mrferos/BinLookup.svg)
![Scrutinizer-CI](https://scrutinizer-ci.com/g/mrferos/BinLookup/badges/quality-score.png?b=master)

BinLookup
==========

A tool to lookup [Credit Card BINs](http://en.wikipedia.org/wiki/Bank_card_number) from one or more providers easily.

### Usage

Using the BinLookup library is simple, first instantiate the BinLookup class:
```php
$binLookup = new BinLookup\BinLookup();
```
Add a Provider

```php
$binLookup->addProvider(new \BinLookup\Provider\BinListNetApi());
```
And perform a search
```php
var_dump($binLookup->search(431940));
```
Output:
```
class BinLookup\Provider\Result#14 (10) {
  protected $_bin =>
  string(6) "431940"
  protected $_brand =>
  string(4) "VISA"
  protected $_countryCode =>
  string(2) "IE"
  protected $_country =>
  string(7) "Ireland"
  protected $_bank =>
  string(15) "BANK OF IRELAND"
  protected $_cardType =>
  string(5) "DEBIT"
  protected $_latitude =>
  NULL
  protected $_longitude =>
  NULL
  protected $_cardSubBrand =>
  NULL
  protected $_cardCategory =>
  string(0) ""
}
```

### Installing via Composer

The recommended way to install BinLookup is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of BinLookup:

```bash
composer require mrferos/bin-lookup
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```
