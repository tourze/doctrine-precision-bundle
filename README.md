# Doctrine Precision Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue?style=flat-square)](https://www.php.net/)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E6.4-green?style=flat-square)](https://symfony.com/)
[![License](https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square)](LICENSE)

[![Build Status](https://img.shields.io/github/workflow/status/tourze/doctrine-precision-bundle/CI?style=flat-square)](https://github.com/tourze/doctrine-precision-bundle/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/doctrine-precision-bundle?style=flat-square)](https://codecov.io/gh/tourze/doctrine-precision-bundle)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-precision-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-precision-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-precision-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-precision-bundle)

A Symfony bundle that enhances Doctrine ORM by providing automatic precision 
control for decimal fields using PHP attributes.

## Features

- ✅ Automatically applies consistent precision to decimal fields across 
  your application
- ✅ Uses environment variables to configure precision values
- ✅ Simple PHP 8+ attribute-based implementation
- ✅ Seamless integration with Doctrine ORM event system
- ✅ Supports PHP 8.1+ and Symfony 6.4+
- ✅ Zero configuration required for basic usage

## Installation

```bash
composer require tourze/doctrine-precision-bundle
```

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher

## Quick Start

### 1. Register the bundle

The bundle should be automatically registered if you're using Symfony Flex. 
If not, add it to your `config/bundles.php`:

```php
<?php

return [
    // ...
    Tourze\DoctrinePrecisionBundle\DoctrinePrecisionBundle::class => ['all' => true],
];
```

### 2. Configure precision value

Set the default precision value in your `.env` file:

```env
DEFAULT_PRICE_PRECISION=2
```

### 3. Use the attribute in your entities

```php
<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10)]
    #[PrecisionColumn]
    private ?string $price = null;

    // Without PrecisionColumn attribute - uses fixed scale
    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 4)]
    private ?string $fixedPrice = null;

    // getters and setters
}
```

The `PrecisionColumn` attribute will automatically set the scale (decimal places) 
according to the `DEFAULT_PRICE_PRECISION` environment variable.

## How It Works

The bundle registers a Doctrine event listener that intercepts the 
`loadClassMetadata` event. When it finds properties marked with the 
`PrecisionColumn` attribute, it automatically sets the scale value for decimal 
fields based on the `DEFAULT_PRICE_PRECISION` environment variable.

### Key Components

- **`PrecisionColumn` Attribute**: PHP 8+ attribute to mark decimal fields 
  for automatic precision
- **`PrecisionListener`**: Doctrine event subscriber that processes metadata 
  during application startup
- **Environment Configuration**: Uses `DEFAULT_PRICE_PRECISION` environment 
  variable (defaults to 2)

## Configuration

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `DEFAULT_PRICE_PRECISION` | `2` | Number of decimal places for fields marked with `PrecisionColumn` |

### Example `.env` Configuration

```env
# Set decimal precision to 3 places (e.g., 123.456)
DEFAULT_PRICE_PRECISION=3

# Set decimal precision to 4 places (e.g., 123.4567)
DEFAULT_PRICE_PRECISION=4
```

## Advanced Usage

### Multiple Precision Types

You can use different precision values for different field types by creating multiple environment variables:

```php
// In your listener or service
$priceScale = intval($_ENV['DEFAULT_PRICE_PRECISION'] ?? 2);
$currencyScale = intval($_ENV['DEFAULT_CURRENCY_PRECISION'] ?? 4);
```

### Custom Attribute Implementation

The bundle uses a simple attribute-based approach that can be extended:

```php
<?php

namespace App\Attribute;

use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class CurrencyPrecision extends PrecisionColumn
{
    // Custom implementation for currency fields
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/tourze/doctrine-precision-bundle.git
cd doctrine-precision-bundle

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit

# Run static analysis
./vendor/bin/phpstan analyse
```

## License

This bundle is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
