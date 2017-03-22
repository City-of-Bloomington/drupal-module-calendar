# drupal-module-calendar
Drupal module to render Google Calendar Events as a block

## Installation
### Composer
We are using [Composer to install and manage our Drupal sites](https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies).


However, because this module is not hosted on Drupal.org, you must add our Github repository
to your composer.json before adding the require statement.

Also, this module requires the Google API Client library.  This is available through the normal
Drupal repositories.  Make sure to add it, as well, to your composer.json as a "require".

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "City-of-Bloomington/calendar",
                "type": "drupal-module",
                "version": "dev",
                "source": {
                    "type": "git",
                    "url": "https://github.com/City-of-Bloomington/drupal-module-calendar",
                    "reference": "master"
                }
            }
        }
    ],
    "require": {
        "google/apiclient": "~2.0",
        "City-of-Bloomington/calendar": "dev"
    }
}
```

### Google Service Account
In order to make queries to your Google Calendar, you must register for an API key,
create a Service Account, and download the credentials to your Drupal site.  You
register for API keys on the [Google Developer Console](https://console.developers.google.com).

This module is a "Server-to-Server" application;  Drupal will be making requests as
a user you specify in the module's settings. You will want to read up
on how to set up OAuth 2.0 for your Google Service Account.

https://developers.google.com/identity/protocols/OAuth2ServiceAccount
https://developers.google.com/google-apps/calendar/quickstart/php
https://developers.google.com/api-client-library/php/auth/service-accounts

Once you have a credentials.json file with your private key, you must place the file
in your Drupal installation's /web/sites/default, next to settings.php.

The credentials.json file should look like this:
```json
{
  "private_key_id": "XXXXXX",
  "private_key": "-----BEGIN PRIVATE KEY-----\nXXXX\n-----END PRIVATE KEY-----\n",
  "client_email": "someuser@developer.gserviceaccount.com",
  "client_id": "someuser.apps.googleusercontent.com",
  "type": "service_account"
}
```

