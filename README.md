# VIES VAT Service

Wrapper for the VIES VAT service, to fetch the data as JSON.


## Parsing VAT ID strings

The static `VatID::parse()` method accepts formatted and unformatted VAT ID strings. It automatically strips most commonly used separator characters and whitespace.

The following examples all return a valid `VatID` instance:

```php
VatID::parse('FR 123 456 78');
VatID::parse('FR-12345678');
VatID::parse('[DE]12-34-56-78');
VatID::parse('de12345678'); // Lowercase country code  
```

## Running the testsuite

To run the tests, first rename the `tests/config.dist.php` to `tests/config.php`, and fill out the necessary information to enable live tests against the VIES service.

Tests can be launched on Windows using `run-tests.bat`. On linux, run the following command:

```
vendor/bin/phpunit
```
