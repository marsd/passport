# Laravel Passport w/ Client UUIDs

## Introduction

This is a fork of the `laravel/passport` package with added support for Client UUIDs.
This package will be kept up-to-date with the latest `laravel/passport` version.

This package retains the `oauth_clients.id` column for indexing & internal
use, while adding a `char(36)` `oauth_clients.uuid` column used for public-facing
requests, both authorization and lookup.

## Usage

To start using Client UUIDs, open up your `AuthServiceProvider.php` file and add:

```php
// AuthServiceProvider.php
...

public function boot()
{
    $this->registerPolicies();

    Passport::tokensExpireIn(Carbon::now()->addDays(15));
    Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

    // Add this line along with your other Passport options
    Passport::useClientUUIDs();

    Passport::tokensCan(config('api.scopes_all'));

    Passport::routes();
}

...
```

If you are starting from a fresh installation, the migrations have already been modified to add
a `uuid` column. If you are continuing from a pre-existing installation, you need to run:

`php artisan passport:uuid`

in order to update your current tables. If you are running a custom connection for your database
driver, you'll need to modify the migration file manually to have it use the correct connection.

## License

Laravel Passport is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
