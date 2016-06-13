# Cet

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

CET score inquiry 大学英语四六级成绩查询

## Install

Via Composer

``` bash
$ composer require xu42/cet
```

## Usage

``` php
require_once './vendor/autoload.php';
$zkzh = '123456789101112';
$xm = '张三';
$cetScore = new \Xu42\cet\cetScore();
print_r($cetScore->get($zkzh, $xm));
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Tests unavailable.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please using the issue tracker.

## Credits

- [Xu42](https://github.com/xu42)
- [All Contributors](https://github.com/xu42/cet/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/xu42/cet.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/xu42/cet.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/xu42/cet
[link-downloads]: https://packagist.org/packages/xu42/cet
[link-author]: https://github.com/xu42
[link-contributors]: ../../contributors
