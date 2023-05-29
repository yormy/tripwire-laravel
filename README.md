# Anonymizer Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yormy/tripwire-laravel.svg?style=flat-square)](https://packagist.org/packages/yormy/tripwire-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/yormy/tripwire-laravel.svg?style=flat-square)](https://packagist.org/packages/yormy/tripwire-laravel)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/facade/ignition/run-php-tests?label=Tests)
![Alt text](./coverage.svg)

# Goal
A easy to use comprehensive and secure firewall to prevent hackers getting into your system and frustrates the heck out of them while trying

# Installation
```
composer require yormy/tripwire-laravel

php artisan vendor:publish --tag="ciphersweet-migrations"
php artisan migrate
```

# Publishing config
```
php artisan vendor:publish --tag="ciphersweet-config"
php artisan vendor:publish --provider="Yormy\TripwireLaravel\AnonymizerServiceProvider"
```


# Additional functionality
- Encrypt all models (scan currrent app directory and add custom models in config) (php artisan db:encrypt --key=)
- Add wrapper around key generation to stay consistent in namespace (db:encrypt-generate-key)
- Create blind index on First X Characters

```
Creating a index on the first could of characters.

Use case:
Search database based on a supplied first part of a Name or email address. For example be able to type in freek to search for all email addresses in the database that start with freek

Usage:
->addBlindIndex('email', new BlindIndex('email_index_f5', [new FirstXCharacters(5)]));

The f5 in the index is just my naming convention to show that this index is based on the First 5 characters
```

# Like like search
Search the database with blind index and filter results to mimic like
ie: $found = Member::whereStartsWith('email', 'email_index_f4', 'joe@gmail.');

This searches the db for the first 4 characters based on the index, then it filters the results and returns only the records that start with the full search string

Use case:
I want to search my records for the string 'joe@gmail.com'
I have created a blind index of 4 characters (see other pr)

When using the whereStartsWith I specify the index to use , full search string and it returns only where the results start with the full string

```
$found = Member::whereStartsWith('email', 'email_index_f4', 'joe2@gmail.com');
```




## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yormy](https://gitlab.com/yormy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
