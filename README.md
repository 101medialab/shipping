# Shipping

Shipping cost estimation library.

[![Build Status](https://travis-ci.org/101medialab/shipping.png?branch=master)](https://travis-ci.org/101medialab/shipping)

## Installation

The recommended way to install Shipping is through composer:

```json
{
    "require": {
        "101medialab/shipping": "~1.0"
    }
}
```

## Usage

```php
/* @var Sylius\Component\Shipping\Model\ShipmentInterface $shipment */
/* @var Sylius\Component\Addressing\Model\AddressInterface $source, $destination */

$calculator = new MediaLab\Shipping\Calculator\FedExCalculator($key, $password, $accountNumber, $meterNumber);
$calculator->calculate($source, $destination, $shipment);
```

## License

101medialab shipping is licensed under the MIT License - see the LICENSE file for details.
