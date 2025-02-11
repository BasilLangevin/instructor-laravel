# Contributing

Thank you for considering contributing to Laravel Instructor! This document outlines the standards and process for contributing to this package.

## Development Requirements

- PHP 8.3+
- Laravel 11+
- Composer

## Quality Standards

All contributions must meet the following quality standards:

- 100% test coverage using Pest
- 100% mutation coverage using Infection
- PHPStan level 10 compliance
- Code style compliance with Laravel Pint

## Development Process

1. Fork the repository
2. Create a new branch for your feature or fix
3. Write your code and tests
4. Ensure all tests pass and quality standards are met
5. Submit a pull request

## Running Tests and Quality Checks

Before submitting your pull request, ensure all tests and quality checks pass:

```bash
# Run the test suite with coverage report
./vendor/bin/pest --coverage --parallel

# Run mutation tests
./vendor/bin/pest --mutate --parallel --covered-only

# Run static analysis
./vendor/bin/phpstan analyse

# Format code
./vendor/bin/pint
```

## Pull Request Guidelines

1. Your pull request should target the `main` branch
2. Include a clear description of the changes
3. Reference any related issues
4. Update documentation as needed
5. Add tests for new functionality
6. Ensure all checks pass in the CI pipeline

## Code Style

This package follows the PSR-12 coding standard and the PSR-4 autoloading standard. We use Laravel Pint for code style enforcement:

```bash
./vendor/bin/pint
```

## Writing Tests

- Each PHP file should have a corresponding Pest test file named `{FileName}Test.php`
- Integration tests should be placed in the `tests/Integration` directory
- All code paths must be tested

## Documentation

- Update the README.md if you're adding or modifying features
- Keep the documentation clear and concise
- Include code examples where appropriate
- Update the CHANGELOG.md with your changes

## Questions or Problems?

If you have any questions or problems, please:

1. Check existing issues and pull requests
2. Open a new issue if needed
3. Ask questions in the pull request

## Security Vulnerabilities

If you discover a security vulnerability, please follow the security policy outlined in the repository. Do not report security vulnerabilities through public GitHub issues.

## License

By contributing to this package, you agree that your contributions will be licensed under its MIT license.
