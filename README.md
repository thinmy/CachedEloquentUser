# CachedEloquentUser
--------------------

Caches authenticated user information to prevent laravel query on every request.

## Instalation

### 1. Dependencies

Using <a href="https://getcomposer.org/" target="_blank">composer</a>, execute the following command to automatically update your `composer.json`:

```shell
composer require thinmy/cached-eloquent-user
```

or manually update `composer.json`

```json
{
	"require": {
		"thinmy/cached-eloquent-user": "^0.0.1"
	}
}
```

After updating composer, add the ServiceProvider to the providers array in `config/app.php`.

```php
    'providers' => [
		Thinmy\CachedEloquentUser\AuthServiceProvider::class,
    ],
```

After updating providers, update your authentication driver in `config/auth.php`.

```php
    'driver' => 'cachedEloquent`;
```