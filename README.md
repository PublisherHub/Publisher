# Publisher
Publish and post via OAuth1 and OAuth2 services.

Based on ([lusitanian/oauth (PHPoAuthLib)](https://github.com/Lusitanian/PHPoAuthLib)) and can be found on [Packagist](https://packagist.org/packages/jlueke/publisher).
The recommended way to install this is through [composer](http://getcomposer.org).

Edit your `composer.json` and add:

```json
{
    "require": {
        "jlueke/publisher": "dev-master"
    }
}
```

And install dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

Included service implementations
--------------------------------
- OAuth1
    - Twitter
    - Xing
- OAuth2
    - Facebook
    - Google (not testet because Google API allows only read)
- more to come!

Examples
--------
Examples of basic usage are located in the examples/ directory.