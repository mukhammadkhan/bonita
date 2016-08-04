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

### simple declaration for home and about pages

```
<?

use Bonita\Router;

Router::get( '/', function(){
  echo 'welcome to our site!';
});

Router::get( '/about', function(){
  echo 'This is our company.';
});
```
### dynamic page declaration

```
<?

use Bonita\Router;

Router::get( '/user/:anyName', function( $username ){
  echo "welcome to $username's page";
});
```
### dynamic page declaration with regular expression

```
<?

use Bonita\Router;

Router::get( '/id/?:anyName:#([0-9]+})#', function( $id ){
  echo "ID number is: $id";
});
```
