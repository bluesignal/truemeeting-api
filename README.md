<p align="center">
  <img src="header.svg" alt="TrueMeeting header">
</p>
<h1 align="center">TrueMeeting PHP API</h1>
<p align="center">
    <a href="https://truemeeting.io">TrueMeeting</a> makes it easy to create video conference calls.
    This repository contains the php client api to easily integrate TrueMeeting into your product.
</p>
<p align="center">
    <a href="https://travis-ci.org/bluesignal/truemeeting-api"><img src="https://travis-ci.org/bluesignal/truemeeting-api.png"></a>
    <a href="https://packagist.org/packages/bluesignal/truemeeting-api"><img src="https://poser.pugx.org/bluesignal/truemeeting-api/v/stable"></a>
</p>

---

Install with composer
---------------------
The php api client of TrueMeeting can be easily installed with the help of
[Composer](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bluesignal/truemeeting-api
```

Getting started
---------------
The use of the api requires you to have an api key.
Just contact support to request your own.


### Creating your first conference room

```php
$client = new TrueMeetingClient('token');
$room = $client->createRoom('Demo', 'Welcome to the demo room');
```
Documentation
-------------
The full documentation can be found at the link below.

[TrueMeeting Documentation](https://developers.truemeeting.io)

License
-------
The TrueMeeting Api client is released under the MIT license, for more details see the LICENSE file.



TrueMeeting is a product of [BlueSignal](https://bluesignal.nl)