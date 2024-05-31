# SIMANANG API

This package is used to interact with the SIMANANG API belonging to SMAN 1 Enam Lingkung.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rangkotodotcom/simanang.svg?style=flat-square)](https://packagist.org/packages/rangkotodotcom/simanang)
[![Total Downloads](https://img.shields.io/packagist/dt/rangkotodotcom/simanang.svg?style=flat-square)](https://packagist.org/packages/rangkotodotcom/simanang)

## Installation

You can install the package via composer:

```bash
composer require rangkotodotcom/simanang
```
#### Setup

You must register the service provider :

```php
// config/app.php

'Providers' => [
   // ...
   Rangkotodotcom\Simanang\SimanangServiceProvider::class,
]
```

If you want to make use of the facade you must install it as well :

```php
// config/app.php

'aliases' => [
    // ...
    'Simanang' => Rangkotodotcom\Simanang\Simanang::class,
];
```
Next, You must publish the config file to define your SIMANANG CREDENTIAL :

```bash
php artisan vendor:publish --tag="simanang-config"
```
This is the contents of the published file :

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Simanang Mode
    |--------------------------------------------------------------------------
    |
    | By default, use development. Supported Mode: "development", "production"
    |
    */

    'simanang_mode' => env('SIMANANG_MODE', 'development'),

    /*
    |--------------------------------------------------------------------------
    | Simanang Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID from SIMANANG API
    |
    */

    'simanang_client_id' => env('SIMANANG_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Simanang Client Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret from SIMANANG API
    |
    */

    'simanang_client_secret' => env('SIMANANG_CLIENT_SECRET', ''),
];
```

Set your SIMANANG CREDENTIAL in `.env` file :

```
APP_NAME="Laravel"
# ...
SIMANANG_MODE=developmentOrProduction
SIMANANG_CLIENT_ID=putYourClientIdHere
SIMANANG_CLIENT_SECRET=putYourClientSecretHere
```

### Methods Ref

- `::getSchool()`

- `::getVision()`

- `::getMision()`

- `::getGallery()`

- `::getHeadMaster()`

- `::getCurrentSemester()`

- `::getStudent()`

- `::getTeacher()`

- `::validasiQrCode()`

- `::storeQrCode()`


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [jamilur rusydi](https://github.com/rangkotodotcom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
