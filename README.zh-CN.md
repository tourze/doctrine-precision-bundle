# Doctrine 精度增强包

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue?style=flat-square)](https://www.php.net/)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E6.4-green?style=flat-square)](https://symfony.com/)
[![License](https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square)](LICENSE)

[![Build Status](https://img.shields.io/github/workflow/status/tourze/doctrine-precision-bundle/CI?style=flat-square)](https://github.com/tourze/doctrine-precision-bundle/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/doctrine-precision-bundle?style=flat-square)](https://codecov.io/gh/tourze/doctrine-precision-bundle)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-precision-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-precision-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-precision-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-precision-bundle)

一个增强 Doctrine ORM 的 Symfony 包，使用 PHP 属性为小数字段提供自动精度控制。

## 功能特性

- ✅ 自动为应用程序中的小数字段应用一致的精度
- ✅ 使用环境变量配置精度值
- ✅ 基于 PHP 8+ 属性（Attribute）的简单实现
- ✅ 与 Doctrine ORM 事件系统无缝集成
- ✅ 支持 PHP 8.1+ 和 Symfony 6.4+
- ✅ 基础用法无需任何配置

## 安装

```bash
composer require tourze/doctrine-precision-bundle
```

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本

## 快速开始

### 1. 注册 Bundle

如果您使用 Symfony Flex，该 Bundle 应该会被自动注册。如果没有，请将其添加到 `config/bundles.php` 中：

```php
<?php

return [
    // ...
    Tourze\DoctrinePrecisionBundle\DoctrinePrecisionBundle::class => ['all' => true],
];
```

### 2. 配置精度值

在 `.env` 文件中设置默认精度值：

```env
DEFAULT_PRICE_PRECISION=2
```

### 3. 在实体中使用属性

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

    // 没有 PrecisionColumn 属性 - 使用固定精度
    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 4)]
    private ?string $fixedPrice = null;

    // getter 和 setter 方法
}
```

`PrecisionColumn` 属性将根据 `DEFAULT_PRICE_PRECISION` 环境变量自动设置小数位数（scale）。

## 工作原理

该 Bundle 注册了一个 Doctrine 事件监听器，拦截 `loadClassMetadata` 事件。
当它找到标记有 `PrecisionColumn` 属性的属性时，它会根据 `DEFAULT_PRICE_PRECISION` 
环境变量自动为小数字段设置精度值。

### 核心组件

- **`PrecisionColumn` 属性**: PHP 8+ 属性，用于标记需要自动精度控制的小数字段
- **`PrecisionListener`**: Doctrine 事件订阅者，在应用程序启动时处理元数据
- **环境配置**: 使用 `DEFAULT_PRICE_PRECISION` 环境变量（默认值为 2）

## 配置

### 环境变量

| 变量 | 默认值 | 描述 |
|------|--------|------|
| `DEFAULT_PRICE_PRECISION` | `2` | 标记有 `PrecisionColumn` 字段的小数位数 |

### 示例 `.env` 配置

```env
# 设置小数精度为 3 位（例如：123.456）
DEFAULT_PRICE_PRECISION=3

# 设置小数精度为 4 位（例如：123.4567）
DEFAULT_PRICE_PRECISION=4
```

## 高级用法

### 多种精度类型

您可以通过创建多个环境变量为不同字段类型使用不同的精度值：

```php
// 在监听器或服务中
$priceScale = intval($_ENV['DEFAULT_PRICE_PRECISION'] ?? 2);
$currencyScale = intval($_ENV['DEFAULT_CURRENCY_PRECISION'] ?? 4);
```

### 自定义属性实现

该包使用简单的基于属性的方法，可以扩展：

```php
<?php

namespace App\Attribute;

use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class CurrencyPrecision extends PrecisionColumn
{
    // 货币字段的自定义实现
}
```

## 贡献

欢迎贡献！请随时提交 Pull Request。

### 开发环境设置

```bash
# 克隆仓库
git clone https://github.com/tourze/doctrine-precision-bundle.git
cd doctrine-precision-bundle

# 安装依赖
composer install

# 运行测试
./vendor/bin/phpunit

# 运行静态分析
./vendor/bin/phpstan analyse
```

## 许可证

此 Bundle 基于 MIT 许可证发布。有关详细信息，请参阅捆绑的 [LICENSE](LICENSE) 文件。
