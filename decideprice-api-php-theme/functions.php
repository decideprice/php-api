<?php
class common 
{
	public function __construct()
		{
			
		}

	//set cookies of cart
	function set_cart_cookie( $merchant, $shop, $product )
	{
		$key_val = $merchant . '_' . $shop . '_' . $product;
		$flag	 = 1;
		if ( isset( $_COOKIE[ 'decideprice_cart' ] ) )
		{
			$cart_data	 = stripcslashes( $_COOKIE[ 'decideprice_cart' ] );
			$cart_array	 = json_decode( $cart_data, TRUE );
			foreach ( $cart_array as $key => $value )
			{
				if ( $value[ 'merchant' ] == $merchant && $value[ 'shop' ] == $shop && $value[ 'product' ] == $product )
				{
					$flag = 0;
				}
			}
			if ( $flag === 1 )
			{
				if ( isset( $_COOKIE[ 'decideprice_cart_count' ] ) )
				{
					$cart_count_data = stripcslashes( $_COOKIE[ 'decideprice_cart_count' ] );
					$cart_count		 = json_decode( $cart_count_data, TRUE );
				}
				if ( sizeof( $cart_count ) <= 0 )
				{
					$cart_count[ 0 ][ $key_val ] = 1;
				}
				else
				{

					foreach ( $cart_count as $key => $value )
					{
						$cart_count[ $key ][ $key_val ] = 1;
					}
				}
				$cart_count_json = json_encode( $cart_count, TRUE );
				@setcookie( "decideprice_cart_count", $cart_count_json, time() + 86400, '/', NULL, 0 );
				$cart_array[]	 = array(
					'merchant'	 => $merchant,
					'shop'		 => $shop,
					'product'	 => $product
				);
				$cart_json		 = json_encode( $cart_array, TRUE );
				@setcookie( "decideprice_cart", $cart_json, time() + 86400, '/', NULL, 0 );

				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			if ( isset( $_COOKIE[ 'decideprice_cart_count' ] ) )
			{
				$cart_count_data = stripcslashes( $_COOKIE[ 'decideprice_cart_count' ] );
				$cart_count		 = json_decode( $cart_count_data, TRUE );

			}else{

				$cart_count = array();
			}

			if ( sizeof( $cart_count ) <= 0 or $cart_count ==NULL )
				{
					$cart_count[ 0 ][ $key_val ] = 1;
				}
			
			else
			{

				foreach ( $cart_count as $key => $value )
				{
					$cart_count[ $key ][ $key_val ] = 1;
				}
			}
			$cart_count_json = json_encode( $cart_count, TRUE );
			@setcookie( "decideprice_cart_count", $cart_count_json, time() + 86400, '/', NULL, 0 );
			$cart_array[]	 = array(
				'merchant'	 => $merchant,
				'shop'		 => $shop,
				'product'	 => $product
			);
			$cart_json		 = json_encode( $cart_array, TRUE );
			@setcookie( "decideprice_cart", $cart_json, time() + 86400, '/', NULL, 0 );

			return TRUE;
		}
	}	

	//unset cookies of cart
	function remove_cart_cookie( $merchant, $shop, $product )
	{
		$end_msg = '';
		$flag	 = 1;
		if ( isset( $_COOKIE[ 'decideprice_cart' ] ) )
		{
			$cart_data	 = stripcslashes( $_COOKIE[ 'decideprice_cart' ] );
			$cart_array	 = json_decode( $cart_data, TRUE );
			if ( sizeof( $cart_array ) <= 1 )
			{
				@setcookie( "decideprice_cart", '', time() - 3000, '/', NULL, 0 );
				@setcookie( "decideprice_cart_count", '', time() - 3000, '/', NULL, 0 );
				return 'empty';
			}
			else
			{
				foreach ( $cart_array as $key => $value )
				{
					if ( $value[ 'merchant' ] == $merchant && $value[ 'shop' ] == $shop && $value[ 'product' ] == $product )
					{
						if ( isset( $_COOKIE[ 'decideprice_cart_count' ] ) )
						{
							$cart_count_data	 = stripcslashes( $_COOKIE[ 'decideprice_cart_count' ] );
							$cart_count_array	 = json_decode( $cart_count_data, TRUE );
							foreach ( $cart_count_array as $key1 => $value1 )
							{
								unset( $cart_count_array[ $key1 ][ $merchant . '_' . $shop . '_' . $product ] );
								$cart_json = json_encode( $cart_count_array, TRUE );
								@setcookie( "decideprice_cart_count", $cart_json, time() + 86400, '/', NULL, 0 );
							}
						}
						unset( $cart_array[ $key ] );
						$flag = 0;
					}
				}
			}

			if ( $flag === 0 )
			{
				$cart_json = json_encode( $cart_array, TRUE );
				@setcookie( "decideprice_cart", $cart_json, time() + 86400, '/', NULL, 0 );

				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			$cart_array[]	 = array(
				'merchant'	 => $merchant,
				'shop'		 => $shop,
				'product'	 => $product
			);
			$cart_json		 = json_encode( $cart_array, TRUE );
			@setcookie( "cart", $cart_json, time() + 86400, '/', NULL, 0 );

			return TRUE;
		}
	}




	function sanitize( $input )
	{
	    if( is_array( $input ) )
	    {
	        foreach( $input as $var => $val )
	        {
	            $output[ $var ] = sanitize( $val );
	        }
	    }
	    else
	    {
	        if( get_magic_quotes_gpc() )
	        {
	            $input = stripslashes( $input );
	        }
	        $input  = $this->cleanInput( $input );
	        $output = mysql_real_escape_string( $input );
	    }
	    return $output;
	}
	function cleanInput( $input )
	{

	    $search = array(
	        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
	        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
	        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
	        '@<![\s\S]*?--[ \t\n\r]*>@'   // Strip multi-line comments
	    );

	    $output = preg_replace( $search, '', $input );
	    return $output;
	}

	}	
?>