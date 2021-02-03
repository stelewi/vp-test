# VP Tech Test Solution

Completed by Steve Lewis, Feb 2021

## Environment and Setup

Expected PHP Version: `7.4`

This solution uses the `vlucas/phpdotenv` package to inject two environment variables: 

```
API_ENDPOINT=https://deathstar.dev-tests.vp-ops.com/alliance.php
DEFAULT_DROID_SENDER_NAME=AdmiralAckbar
```

Environment variables are supplied in a base `.env` file but can be overridden.

`composer install` should be run to install any required vendor packages.

## Running the Script

From the project root, simply run:

`php bin/discover-path.php`

Or, for verbose output:

`php bin/discover-path.php -v`

Or, if you want to override the default name sent when making api calls:

`php bin/discover-path.php "<your_name>"`

Or, a combination...

## Easter Egg

Use the __very verbose mode__ to see realtime map info:

`php bin/discover-path.php "<your_name>" -vv`


## Tests

Tests can be found under the `spec` directory. I tried to write a test for anything that contains any non-trivial logic.

To run the suite of `phpspec` tests:

From the project root, simply run:

`php vendor/phpspec/phpspec/bin/phpspec run`
