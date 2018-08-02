# Ebb - generate a json schema for a CiviCRM instance

Useful for things like:

* Angular Schematics to generate a Material Design user interface for CiviCRM
* generating a RAML API for CiviCRM

## Usage

```
$ composer global require michaelmcandrew/ebb
$ ebb generate "https://example.org/sites/all/modules/civicrm/extern/rest.php?key=xxx&api_key=xxx"
```
