# bonita
Bonita packages

## How to use?

```
<?

require_once 'Router.php';

use Bonita\Router;

Router::get( '/', function(){
  echo 'Home page';
});

Router::get( '/user/:name', function( $username ){
	echo "welcome to " .ucfirst( $username ) . "'s page!";
} );
