Vonage Nexmo Bridge
===================
[![Build Status](https://github.com/nexmo/vonage-php-nexmo-bridge/workflows/build/badge.svg?branch=master)](https://github.com/nexmo/vonage-php-nexmo-bridge/actions?query=workflow%3Abuild)
[![MIT licensed](https://img.shields.io/badge/license-BSD-blue.svg)](./LICENSE)
[![codecov](https://codecov.io/gh/nexmo/vonage-php-nexmo-bridge/branch/master/graph/badge.svg)](https://codecov.io/gh/nexmo/vonage-php-nexmo-bridge)

<img src="https://developer.nexmo.com/assets/images/Vonage_Nexmo.svg" height="48px" alt="Nexmo is now known as Vonage" />

This library provides a custom autoloader that aliases legacy Nexmo classes, traits, and interfaces
to their replacements under the Vonage namespace.

This library is handy if you want to switch over to the newer `vonage/client` or `vonage/client-core` libraries but have
a lot of older code that utilizes the `\Nexmo` namespace. If you are starting a project from scratch, we recommend immediately
using the `\Vonage` namespace.

Much of this code has been ported from the [`laminas/laminas-zendframework-bridge`](https://github.com/laminas/laminas-zendframework-bridge) project.

## Installation

Run the following to install this library:

```bash
$ composer require vonage/nexmo-bridge
```

## Usage

There is none! By including this package, the autoloader will be automatically invoked and will do all the work
for you.

## Contributing

This library is actively developed and we love to hear from you! Please feel free to [create an issue][issues] or [open a pull request][pulls] with your questions, comments, suggestions and feedback.

[issues]: https://github.com/Nexmo/vonage-php-nexmo-bridge/issues
[pulls]: https://github.com/Nexmo/vonage-php-nexmo-bridge/pulls