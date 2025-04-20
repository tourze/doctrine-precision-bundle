# Doctrine 精度增强包

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-precision-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-precision-bundle)

一个增强 Doctrine ORM 的 Symfony 包，提供对小数字段的精度控制。

## 功能特性

- 自动为应用程序中的小数字段应用一致的精度
- 使用环境变量配置精度值
- 基于属性（Attribute）的简单实现
- 与 Doctrine ORM 无缝集成

## 安装

```bash
composer require tourze/doctrine-precision-bundle
```

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

```
DEFAULT_PRICE_PRECISION=2
```

### 3. 在实体中使用属性

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

    // getter 和 setter 方法
}
```

`PrecisionColumn` 属性将根据 `DEFAULT_PRICE_PRECISION` 环境变量自动设置小数位数（scale）。

## 工作原理

该 Bundle 注册了一个 Doctrine 事件监听器，拦截 `loadClassMetadata` 事件。当它找到标记有 `PrecisionColumn` 属性的属性时，它会根据环境配置为小数字段设置精度值。

## 贡献

欢迎贡献！请随时提交 Pull Request。

## 许可证

此 Bundle 基于 MIT 许可证发布。有关详细信息，请参阅捆绑的 [LICENSE](LICENSE) 文件。
