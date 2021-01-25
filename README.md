# Mapp Intelligence - Server-side PHP tracking library

[Site](https://mapp.com/) |
[Docs](https://documentation.mapp.com/latest/en/php-library-12582730.html) |
[Support](https://support.webtrekk.com/) |
[Changelog](https://documentation.mapp.com/latest/en/changelog-12582865.html)

Server-side tracking is crucial for companies needing to measure mission-critical information on their website, such 
as order information or other website goals.

The PHP library of Mapp Intelligence helps customers to setup server-side tracking when using Mapp Intelligence as 
their analysis tool.

The basis for the data collection on your server is implementing the respective library. The Mapp Intelligence tracking 
library provides scripts to track user behavior and technical information, such as user agents, among others. In 
addition to the standard data collection, the tracking library offers many options to customize tracking based on 
specific use cases. Typical use cases are product, order and shipment tracking or the tracking of application processes.

# Development

## Requirements

| Software         | Version     |
|------------------|------------:|
| `docker`         |     `19.0+` |
| `docker-compose` |     `1.24+` |
| `make`           |             |

## Lint

```bash
$ make lint
```

## Test

Test *Mapp Intelligence - Server-side PHP tracking library* inside a docker container.

* `$ make test-all` > run tests for PHP v5.6, v7.4 and latest v8
* `$ make test-php5` > run tests for PHP v5.6
* `$ make test-php7` > run tests for PHP v7.4
* `$ make test-php8` > run tests for latest PHP v8
* `$ make test-latest` > run tests for latest PHP
