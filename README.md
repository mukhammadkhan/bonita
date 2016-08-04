# Bonita Packages

## Bonita Router

**Requirement:**

PHP 5 | PHP 7
------------ | -------------
NO | YES

**Package type:**

part of Bonita | independent
------------ | -------------
YES  | YES

**Supported methods:**

GET | POST
------------ | -------------
YES  | YES

## How to use?

```
<?

use Bonita\Router;

Router::get( '/', function(){
  echo 'welcome to our site';
});
