# JMS/Serializer extension for PHPStan

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev goetas/jms-serializer-phpstan-extension
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!


### Manual installation

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:

```
includes:
    - vendor/goetas/jms-serializer-phpstan-extension/extension.neon
```
