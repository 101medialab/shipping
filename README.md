# Shipping

Shipping cost estimation library.

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

$calculator = new 101medialab\Shipping\Calculator\Fedex();
$calculator->calculate($shipment);
```

## License

101medialab shipping is licensed under the MIT License - see the LICENSE file for details.