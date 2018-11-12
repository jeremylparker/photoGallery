<?php												

	function build_cookie($var_array) 
	{
		if (is_array($var_array)) 
		{
			foreach ($var_array as $index => $data) 
			{
				if ( $index != 'addtocart' && $index != 'gallery_name' && $index != 'photo_id' && $index != 'submit' && $data != '0' )
				{
					if ( $data != "" )
					{
						$already_exists = item_already_exists( $index );
						if ( $already_exists >= 0 )
						{
							update_quantity( $already_exists, $data, TRUE );
						}
						else
						{
							$_SESSION['gallery_name'][] = $_POST['gallery_name'];
							$_SESSION['photo_id'][] = $_POST['photo_id'];
							$_SESSION['product_info'][] = $index;
							$_SESSION['quantity'][] = $data;
						}
					}
				}
			}
		}
	}

	function item_already_exists( $item )
	{
		if ( $_SESSION['product_info'] != "" )
		{
			foreach( $_SESSION['product_info'] as $i => $value ) 
			{
				if ( $_SESSION['gallery_name'][$i] == $_POST['gallery_name'] )
				{
					if ( $_SESSION['photo_id'][$i] == $_POST['photo_id'] )
					{
						if ( $value == $item )
						{
							// yep - it matches all of the parameters. 
							return $i;
						}
					}
				}
			}
		}

		return -1;
	}

	function update_quantity( $idx, $new_quantity, $do_addition )
	{
		$_SESSION['quantity'][$idx] = $do_addition ? $_SESSION['quantity'][$idx] + $new_quantity : $_SESSION['quantity'][$idx] - $new_quantity;
	}

	function break_cookie( $cookie_string )
	{
		$array = explode( "|", $cookie_string );

		foreach( $array as $i => $stuff ) 
		{
			$stuff = explode( ".", $stuff );
			$array[$stuff[0]] = $stuff[1];
			unset( $array[$i] );
		}

		return $array;
	}	

	function count_cart_items()
	{
		$count = 0;

		if ( isset( $_SESSION['quantity'] ) )
		{
			foreach( $_SESSION['quantity'] as $item => $value )
			{
				$integer_val = intval( $value );
				$count = $count + $integer_val;
			}						
		}

		return $count;
	}

	function writeShoppingCart() 
	{
		$page_number = $_GET['p'];
		if ( !isset( $page_number ) )
			$page_number = 0;

		$cart = $_SESSION['product_info'];
		$count = 0;
		if (!$cart) 
		{
			echo '<a href="viewcart.php?p=$page_number">You have no items in your shopping cart</a>';
		} 
		else 
		{
			$count = count_cart_items();
			$s = ($count > 1) ? 's':'';
			echo "<a href=\"viewcart.php?p=$page_number\">You have $count item$s in your shopping cart</a>";
		}
		return $count;
	}	

	function add_items_to_cart()
	{
		if ( isset( $_SESSION ) && isset( $_POST['addtocart'] ) )
			build_cookie( $_POST );
	}

	function remove_item_from_cart( $idx )
	{
		unset( $_SESSION['gallery_name'][$idx] );
		unset( $_SESSION['photo_id'][$idx] );
		unset( $_SESSION['product_info'][$idx] );
		unset( $_SESSION['quantity'][$idx] );
	}

	function update_cart()
	{
		if ( isset( $_POST["updatecart"] ) )
		{
			if ( $_SESSION['product_info'] != "" )
			{
				foreach( $_SESSION['product_info'] as $i => $value ) 
				{
					$post_val = 'quant_update_'.$i;
					$new_val = $_POST[$post_val];

					if ( $new_val <= 0 )
					{
						// let's delete it
						remove_item_from_cart( $i );
					}
					else
					{
						$_SESSION['quantity'][$i] = $new_val;
					}
				}
			}
		}
	}

	function get_price( $product )
	{
		$price = 0.00;
		switch ( $product )
		{
			case( "4x6_quantity" ) :
				$price = 25.00;
			break;

			case( "5x7_quantity" ) :
				$price = 35.00;
			break;

			case( "8x10_quantity" ) :
				$price = 50.00;
			break;

			case( "8x12_quantity" ) :
				$price = 55.00;
			break;

			case( "11x14_quantity" ) :
				$price = 100.00;
			break;

			case( "12x18_quantity" ) :
				$price = 150.00;
			break;

			case( "16x24_quantity" ) :
				$price = 225.00;
			break;

			case( "20x30_quantity" ) :
				$price = 300.00;
			break;

			case( "24x36_quantity" ) :
				$price = 375.00;
			break;

			case( "30x40_quantity" ) :
				$price = 500.00;
			break;

			case( "30x45_quantity" ) :
				$price = 550.00;
			break;
		}

		return $price;
	}

	function get_product_name( $product )
	{
		return substr( $product, 0, -9 );
	}
?>
