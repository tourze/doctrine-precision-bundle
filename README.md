# Doctrine Precision Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-precision-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-precision-bundle)

A Symfony bundle that enhances Doctrine ORM by providing precision control for decimal fields.

## Features

- Automatically applies consistent precision to decimal fields across your application
- Uses environment variables to configure precision values
- Simple attribute-based implementation
- Seamless integration with Doctrine ORM

## Installation

```bash
composer require tourze/doctrine-precision-bundle
```

## Quick Start

### 1. Register the bundle

The bundle should be automatically registered if you're using Symfony Flex. If not, add it to your `config/bundles.php`:

```php
<?php

return [
    // ...
    Tourze\DoctrinePrecisionBundle\DoctrinePrecisionBundle::class => ['all' => true],
];
```

### 2. Configure precision value

Set the default precision value in your `.env` file:

```
DEFAULT_PRICE_PRECISION=2
```

### 3. Use the attribute in your entities

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10)]
    #[PrecisionColumn]
    private ?string $price = null;

    // getters and setters
}
```

The `PrecisionColumn` attribute will automatically set the scale (decimal places) according to the `DEFAULT_PRICE_PRECISION` environment variable.

## How It Works

The bundle registers a Doctrine event listener that intercepts the `loadClassMetadata` event. When it finds properties marked with the `PrecisionColumn` attribute, it sets the scale value for decimal fields based on the environment configuration.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This bundle is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
