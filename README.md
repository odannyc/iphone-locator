# iphone-locator
A simple tool to retrieve your iPhone's location using a reversed engineered iCloud API.

## Installation
To install this simple PHP client you simply need to use composer:

`composer install odannyc/iphone-locator`

If you don't have composer, well you'll need to [download](https://getcomposer.org/doc/00-intro.md) and learn about it.

## Usage
To use, you simply need to call 1 simple method:

```php
$username = 'test'; // This is the username associated with the iCloud account.
$password = 'password'; // This is the password associated with the iCloud account.

$locator = new iPhoneLocator($username, $password);

$locator->devices(); // Gets you all devices associated to the account with all their info.
```

It is still in current development, but that `$locator->devices()` method will get you all the information you need, and more ;)

## Contributions
If you'd like to contribute, make sure to open an issue first explaining what feature you'll be implementing or bug you'll be fixing. Then, do the work and open a pull request on master.

## Thank You
Thanks to all the current iphone locator clients there is for PHP in Github right now; I got some ideas from them.
But this one is modernized and you can install it with Composer, which the other ones lack.
