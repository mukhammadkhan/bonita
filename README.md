```
<?

require_once 'Router.php';

use Bonita\Router;

# home page definition

Router::get( '/', function(){
	echo 'Home page';
});

# dynamic page

Router::get( '/user/:name', function( $username ){
	echo "welcome to " .ucfirst( $username ) . "'s page!";
});

# use regular experssion (pattern: #^([a-z-]+)([0-9]{4})$#i)

# regex starting with "?" on declaration

Router::get( '/id/?::#^([a-z-]+)([0-9]{4})$#i', function( $id ){

    echo "ID: $id";
});
