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
