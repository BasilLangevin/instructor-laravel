# Structured outputs for LLMs using Spatie Data objects.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basillangevin/instructor-laravel.svg?style=flat-square)](https://packagist.org/packages/basillangevin/instructor-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/basillangevin/instructor-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/basillangevin/instructor-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/basillangevin/instructor-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/basillangevin/instructor-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/basillangevin/instructor-laravel.svg?style=flat-square)](https://packagist.org/packages/basillangevin/instructor-laravel)

Instructor Laravel turns LLM (Large Language Model) responses into [spatie/laravel-data](https://spatie.be/docs/laravel-data/v4/introduction) objects, helping you easily integrate LLMs into your applications.

## Installation

You can install the package via composer:

```bash
composer require basillangevin/instructor-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="instructor-laravel-config"
```

These are the contents of the published config file:

```php
use EchoLabs\Prism\Enums\Provider;

// config for BasilLangevin/InstructorLaravel
return [
    /*
    |--------------------------------------------------------------------------
    | Default LLM Provider
    |--------------------------------------------------------------------------
    |
    | This value is the default LLM provider that this package will use to
    | generate a structured response that it will transform into a Data
    | object. You may also set LLM providers on a per-request basis.
    */
    'provider' => Provider::OpenAI,

    /*
    |--------------------------------------------------------------------------
    | Default LLM Model
    |--------------------------------------------------------------------------
    |
    | This value is the default LLM model that this package will use
    | for the LLM provider when generating a structured response.
    | You may also set the model each time you make a request.
    */
    'model' => 'gpt-4o',
];
```

## Introduction

Instructor Laravel is built on top of [echolabsdev/prism](https://prism.echolabs.dev/): a fantastic package that provides a unified interface to work with various LLM providers.

Instructor Laravel extends the [Structured Outputs](https://prism.echolabs.dev/core-concepts/structured-output.html) feature of Prism, allowing you to use [spatie/laravel-data](https://spatie.be/docs/laravel-data/v4/introduction) objects as your data schema.

The package modifies two methods from Prism: `withSchema` and `generate`. All other Prism methods are available and work as expected.

## Basic Usage

Because this package transforms LLM responses into `spatie/laravel-data` objects, we'll start by defining a `Data` object.

```php
use Spatie\LaravelData\Attributes\Min;
use Spatie\LaravelData\Data;

class BirdData extends Data
{
    public function __construct(
        public string $species,

        /** The average wingspan of the bird in centimeters. */
        public int $wingspan,

        #[In(['forest', 'prarie', 'wetland'])]
        public string $habitat,
    ) {}
}
```

Then, we'll build our LLM request by:
- starting with the `Instructor` facade,
- passing our `Data` object class to the `withSchema` method,
- using standard Prism [structured output methods](https://prism.echolabs.dev/core-concepts/structured-output.html) to configure the request, then
- calling the `generate` method.

```php
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use EchoLabs\Prism\Enums\Provider;

$bird = Instructor::make()
    ->withSchema(BirdData::class)
    ->using(Provider::OpenAI, 'gpt-4o')
    ->withPrompt('Tell me about a bird found on the West Coast of Canada.')
    ->generate();
```

The `generate` method will return a `BirdData` object from the LLM response.

```php
BirdData {
  +species: "Western Bluebird"
  +wingspan: 34
  +habitat: "forest"
}
```

### Collections of Data objects

Instructor Laravel also supports transforming responses that contain collections of `Data` objects.

```php
$birds = Instructor::make()
    ->withCollectionSchema(BirdData::class)
    ...
```

The `generate` method will return a `Collection`` of `BirdData` objects.

You may pass a custom `Collection` class name as the second argument of `withCollectionSchema` to use a custom collection class:

```php
$birds = Instructor::make()
    ->withCollectionSchema(BirdData::class, BirdCollection::class)
    ...
```

## Validation and retries

Instructor Laravel will automatically validate the LLM response against the `Data` object's rules.

If the LLM response doesn't match the expected structure, Instructor Laravel will call the LLM again with any errors it encountered, helping the LLM generate a response that matches the expected structure.

By default, the request will be retried up to 3 times. You can change this by calling `withRetries(...)` or `withoutRetries()`:

```php
$bird = Instructor::withSchema(BirdData::class)
    ->withRetries(5)
    ...
```

The `withRetries` method accepts the same arguments as Laravel's `retry` helper (excluding the `$callback` argument). The number of retries will be added to the initial request, so if you pass `3` to `withRetries`, the request will be made up to 4 times in total (1 initial attempt + 3 retries).

## Improving LLM response reliability

To improve the LLM's understanding of the expected response, Laravel Instructor transforms the `Data` class into a JSON Schema and passes it to the LLM.

Under the hood, schemas are generated using [basillangevin/laravel-json-data-schema](https://github.com/basillangevin/laravel-json-data-schema). This package adds additional information to the generated schema by transforming:

- PHPDoc block [summaries and descriptions](https://github.com/BasilLangevin/laravel-data-json-schemas?tab=readme-ov-file#schema-annotations),
- most `spatie/laravel-data` [validation attributes](https://github.com/BasilLangevin/laravel-data-json-schemas?tab=readme-ov-file#validation-rules), and
- `Title`, `Description`, and `CustomAnnotation` [attributes](https://github.com/BasilLangevin/laravel-data-json-schemas?tab=readme-ov-file#schema-annotations).

All of this information is available to the LLM from the very first request, so it can be helpful to annotate your `Data` classes more information as needed.

## Testing

Each PHP file in this package has a co-located Pest test file named `{FileName}Test.php`.

This package achieves 100% test coverage, 100% mutation coverage, and 100% PHPStan coverage coverage at level 10.

The following commands can be used to test the package:

```bash
# Run the standard test suite
./vendor/bin/pest --parallel

# Run the test suite and generate a coverage report
./vendor/bin/pest --coverage --parallel

# Run mutation tests
./vendor/bin/pest --mutate --parallel --covered-only

# Run PHPStan at level 10
./vendor/bin/phpstan analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [BasilLangevin](https://github.com/BasilLangevin)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
