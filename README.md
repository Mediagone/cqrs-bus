# CQRS Bus

⚠️ _This project is in experimental phase, the API may change any time._

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

Services are core classes of any application, and keeping them well organized may be a real challenge. Except if you're working on a small RAD or POC project, your services will quickly grow as messy classes.

This package combines two useful patterns:

- [CQRS](https://martinfowler.com/bliki/CQRS.html) which separates actions (commands) from data retrieval (queries) services.
- [Chain of Responsibility](https://refactoring.guru/design-patterns/chain-of-responsibility) which involves chains of middlewares to handle these commands and queries. Possibilities offered by middleware buses are endless and easily extensible.

It will help you to:
- Decouple _read_ and _write_ models.
- Wrap every service calls with generic behaviors: _logging, queuing, security, asynchronous handling..._
- Split your services and repositories into multiple classes (enforce _Single Responsibility Principle_).


## Installation
This package requires **PHP 7.4+** and Doctrine **ORM 2.7+**

Add it as Composer dependency:

```sh
$ composer require mediagone/cqrs-bus
```

## License

_CQRS Bus_ is licensed under MIT license. See LICENSE file.



[ico-version]: https://img.shields.io/packagist/v/mediagone/cqrs-bus.svg
[ico-downloads]: https://img.shields.io/packagist/dt/mediagone/cqrs-bus.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg

[link-packagist]: https://packagist.org/packages/mediagone/cqrs-bus
[link-downloads]: https://packagist.org/packages/mediagone/cqrs-bus
