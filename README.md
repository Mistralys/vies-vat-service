# VIES VAT Service

Wrapper for the VIES VAT service, to fetch the data as JSON.

## Fetching data

###  Sending a request

Simply call the endpoint with the `vatid` parameter:

```
/url/to/endpoint/?vatid=FR00000000
```

The request always returns JSON, with the `status` key. This specifies
if the request itself was successful, not whether the VAT ID is valid.

### Successful request

A successful request returns detailed data on the results. The relevant
part is stored under `data > result`. 

```json
{
  "status": "success",
  "data": {
    "result":{
      "vatID":{
        "countryCode":"FR",
        "number":"00000000"
      },
      "valid":true,
      "identifier":"WJFS4465465SD",
      "date":"2021-03-17T07:17:07+0100",
      "companyName":"---",
      "companyAddress":"---"
    },
    "requester":{
      "name":"Company name",
      "companyType":"",
      "street":"Street address",
      "city":"City",
      "postcode":"00000",
      "vatID":{
        "countryCode":"FR",
        "number":"000000"
      }
    },
    "requesterMatch":{
      "name":"",
      "companyType":"",
      "street":"",
      "postcode":"",
      "city":""
    }
  }
}
```

  > NOTE: The requester information is what has been specified in the
    service configuration, in the `config.php`.

From experience, the company name and address in the result set are missing
more often than not, so it's better not to rely on it. The same goes for
the requester match.

### Failed request

A request can fail for example if the VIES VAT service is down. The error
message details the reason why.

```json
{
  "status": "error",
  "message": "Error message",
  "code": 145
}
```


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
