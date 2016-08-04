<?

/**
*	Bismillahir Rohmanir Rohim
*
*	This file is part of Bonita packages
*/

namespace Bonita;

/**
*	Route base class
*
*	@package Bonita
*	@version 1.0
*	@author Najmuddin Mohammed <najmuddin.mohammed@hotmail.com>
*	@copyright (c) Bonita 2016 <boninta-source.com>
*/

class Router{

	/**
	*	@var const
	*/

	const BON_VER_MAJOR = 1;

	/**
	*	@var const
	*/

	const BON_VER_MINOR = 0;

	/**
	*	@var const
	*/

	const BON_STR_DLM = '~bon';

	/**
	*	@var const
	*/

	const BON_VAL_NONE = '~bon-unknown';

	/**
	*	@var const
	*/

	const BON_VAL_MATCH = '/^bon_val\((?<val>(.*))\)$/';

	/**
	*	@var static
	*/

	protected static $uri = null;
	
	/**
	*	version.
	*
	*	@return string
	*/
	
	public static function get( string $pattern = null, $callback = null ){
		
		if( empty( $pattern ) | !is_callable( $callback ) ) return false;
	}

	/**
	*	version.
	*
	*	@return string
	*/

	public static function version() : string{

		return static::BON_VER_MAJOR . '.' . static::BON_VER_MINOR;
	}

	/**
	*	set_uri.
	*
	*	@return bool
	*/

	public static function set_uri( string $uri ) : bool{

		return static::$uri = $uri;
	}

	/**
	*	uri.
	*
	*	@return array
	*/

	public static function uri() : array{

		$uri = ( is_null( static::$uri ) ) ? $_SERVER['REQUEST_URI'] : static::$uri;

		$uri = static::uri_decode( static::res_key( static::uri_split( '/', $uri ) ) );

		if( $_SERVER['HTTP_HOST'] === 'localhost' & is_null( static::$uri ) ){

			if( isset( $uri[0] ) ) unset( $uri[0] );
			else unset( $uri[1] );
		}

		return static::res_key( $uri );
	}

	/**
	*	current.
	*
	*	@return string
	*/

	public function current() : string{

		return implode( '/', static::uri() );
	}

	/**
	*	set_route.
	*
	*	@param string $pattern
	*	@param callable $callback
	*	@return mixed
	*/

	public static function set_route( string $pattern, callable $callback ){

		foreach ( static::uri_split( '/', $pattern ) as $key => $value ) $_pattern[$key] = ( strlen( $value ) === 1 & $value === ':' ) ? ':' . $key : $value;

		$map = static::uri_map( static::res_key( $_pattern ) );

		$pattern = array_filter( $map, 'static::uri_map_filter', ARRAY_FILTER_USE_BOTH );

		if( 1 === count( $map ) & isset( $map['/'] ) & empty( static::uri()[0] ) ) return $callback();

		if( count( static::uri() ) !== count( $map ) | count( static::uri() ) !== count( $pattern ) ) return false;

		return ( 1 === count( $pattern ) & empty( array_filter( $pattern ) ) ) ? $callback( null ) : eval( '$callback('. implode( ',', static::make_arg( $pattern ) ) . ');' );
	}

	/**
	*	bon_val.
	*
	*	@param mixed $input
	*	@return mixed
	*/

	public function bon_val( $input ){

		preg_match_all( self::BON_VAL_MATCH, $input, $m );

		if( !isset( $m['val'][0] ) ) return static::BON_VAL_NONE;

		$v = $m['val'][0];

		if( is_numeric( $v ) ) return ( strpos( $v, '.' ) ) ? floatval( $v ) : intval( $v );

		if( 'true' 	=== strtolower( $v ) ) return true;
		if( 'false' === strtolower( $v ) ) return false;
		if( 'null' 	=== strtolower( $v ) ) return null;

		return $v;
	}

	/**
	*	make_arg.
	*
	*	@param array $input
	*	@return array
	*/

	protected static function make_arg( array $input ) : array{

		foreach( $input as $key => $val ) if( substr( $key, 0, 1 ) === ':' | substr( $key, 0, 2 ) === '?:' ) $array[$key] = ( is_string( $val ) ) ? "'$val'" : $val;

		return $array ?? ( array ) null;
	}

	/**
	*	uri_map.
	*
	*	@param array $input
	*	@return array
	*/

	protected function uri_map( array $input ) : array{

		if( 1 === count( $input ) & $input[0] === '/' ) return [ '/' => (!empty( static::uri()[0] ) ) ? static::uri()[0] : false ];

		foreach( $input as $key => $name ) $map[$name] = ( !empty( static::uri()[$key] ) ) ? static::uri()[$key] : false;

		return $map;
	}

	/**
	*	uri_map_filter.
	*
	*	@param string $val
	*	@param string $key
	*	@return bool
	*/

	protected static function uri_map_filter( string $val, string $key ) : bool{

		return ( ':' != substr( $key, 0, 1 ) ) ? ( '?' !== substr( $key, 0, 1 ) ) ? $key == $val : 1 === preg_match( ( ':' === substr( $key, 1, 1 ) ) ? trim( substr( $key, strpos( substr( $key, 2 ), ':' ) + 3 ) ) : substr( $key, 1 ), $val ) : true;
	}

	/**
	*	uri_decode.
	*
	*	@param array $array
	*	@return array
	*/

	protected function uri_decode( array $array ) : array{

		return array_map( function( $val ){ return ( 1 !== preg_match( static::BON_VAL_MATCH, $val ) ) ? urldecode( $val ) : $val; }, $array );
	}

	/**
	*	uri_split.
	*
	*	@param string $delimiter
	*	@param string $uri
	*	@return array
	*/

	protected function uri_split( string $delimiter, string $uri ) : array{

		$uri = explode( $delimiter, $uri );

		$arr = array_filter( $uri, function( $arg ){ return ( 0 != strlen( $arg ) ); } );

		return ( !empty( $arr ) ) ? $arr : ['/'];
	}

	/**
	*	res_key.
	*
	*	@param array $input
	*	@return array
	*/

	private function res_key( array $input ) : array{

		return explode( self::BON_STR_DLM, implode( self::BON_STR_DLM, $input ) );
	}
}
