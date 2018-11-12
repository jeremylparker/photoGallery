<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
		session_start();

		if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
		  $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(
			$_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );

		include ($_SERVER["DOCUMENT_ROOT"].'/gallery/include/carthelper.php');

		if ( isset( $_POST["addtocart"] ) )
		{
			// we are trying to add stuff to the cart
			add_items_to_cart();
		}
		else if ( isset( $_POST["updatecart"] ) )
		{
			// we are trying to update the cart
			update_cart();
		}
		else
		{
			// must just be viewing the cart
		}
?>
<head>
	<title>JParker Studio Gallery - View Cart</title>
	<LINK REL=StyleSheet HREF="/gallery/stylesheets/gallery_style.css" TYPE="text/css" MEDIA="all" />
</head>

<body>
	<?php
		$page_number = $_GET['p'];
		if ( !isset( $page_number ) )
			$page_number = 0;

		echo '<div id="cartbar"><div id="toptext">';
		$cart_quantity = writeShoppingCart();
		echo '</div><a href="/"><div id="toplogo"></div></a></div>';
		echo '<div id="mainbar">';
		echo "<form action=\"viewcart.php?p=$page_number\" method=\"post\">";
		echo ( '<center><table id="gallery" cellspacing=15>' );

		if ( $cart_quantity > 0 )
		{
			include ($_SERVER["DOCUMENT_ROOT"].'/gallery/include/thumb_convert.php');
			echo ( '<tr><td class="cartheadercell">Photo</td><td class="cartheadercell">Size</td><td class="cartheadercell">Quantity</td><td class="cartheadercell">Price</td></tr>' );
			$subtotal = 0;

			foreach ( $_SESSION['photo_id'] as $item => $value )
			{
				$photo_idx = intval( $value );
				$product_info = $_SESSION['product_info'][$item];
				$quantity = $_SESSION['quantity'][$item];
				$price = get_price( $product_info );
				$price = sprintf( "%01.2f", $price );
				$subtotal += ( $price * $quantity );
				$subtotal = sprintf( "%01.2f", $subtotal );
				$product_info = get_product_name( $product_info );

				if ($handle = opendir('./images/')) 
				{
					$current_idx = 1;
					while ( false !== ($file = readdir($handle)) ) 
					{
						if ( $file != "." && $file != ".." && get_file_extension( $file ) == "jpg" )
						{
							if ( $current_idx == $photo_idx )
							{
								//render this thumbnail
								echo ( "<tr><td class=\"cartphotocell\"><a href=\"viewphoto.php?i=$photo_idx&p=$page_number\"><img class=\"pic\" style=\"position: relative; top: 0%; margin: 10px;\" src=images/thumbnails/$file /></a></td>" );
								echo ( "<td class=\"cartdatacell\">$product_info</td>" );
								echo ( "<td class=\"cartdatacell\"><input class=\"smalltextinput\" type=\"text\" name=\"quant_update_$item\" value=\"$quantity\" maxlength=\"3\"/></td>" );
								echo ( "<td class=\"cartdatacell\">\$$price</td>" );
								echo ( '</tr>' );
								break;
							}

							$current_idx += 1;
						}
					}
				}
			}

			echo ( "<tr><td class=\"cartbuttoncell\" colspan=\"3\"><a href=\"index.php?p=$page_number\">Continue Shopping</a></td><td class=\"cartbuttoncell\"><input type=\"hidden\" name=\"updatecart\" value=\"1\"/><input type=\"submit\" name=\"submit\" class=\"cartsubmit\" value=\"Update Cart\"/></td></form>" );

//			echo ( "<tr><td class=\"cartdivcell\" colspan=\"4\"></td></tr>" );
			
			echo ( "<tr><td class=\"cartfootercell_noborder\"></td><td class=\"cartfootercell\" colspan=\"2\">Sub-total:</td><td class=\"cartheadercell\">\$$subtotal</td></tr>" );

			// Discount!!!
			$discount = 0.00;
//			$discount = 0.25;
			$disc_val = ($discount*100);
			$savings = $subtotal * $discount;
			$savings = sprintf( "%01.2f", $savings );
			if ( $discount > 0.0 )
				echo ( "<tr><td class=\"cartfootercell_noborder\"></td><td class=\"cartfootercell\" colspan=\"2\">$disc_val% Discount:</td><td class=\"cartheadercell\">\$$savings</td></tr>" );
			$subtotal *= (1.0 - $discount);

			$tax = $subtotal * 0.0925;
			$tax = round( $tax, 2 );
			$tax = sprintf( "%01.2f", $tax );
			$ship_val = $cart_quantity > 4 ? 10.00 : 5.00;
			$shipping = sprintf( "%01.2f", $ship_val );
			echo ( "<tr><td class=\"cartfootercell_noborder\"></td><td class=\"cartfootercell\" colspan=\"2\">Tax (9.25%):</td><td class=\"cartheadercell\">\$$tax</td></tr>" );
			echo ( "<tr><td class=\"cartfootercell_noborder\"></td><td class=\"cartfootercell\" colspan=\"2\">Shipping:</td><td class=\"cartheadercell\">\$$shipping</td></tr>" );
			$total = $subtotal + $tax + $shipping; 
			$total = round( $total, 2 );
			$total = sprintf( "%01.2f", $total );
			echo ( "<tr><td class=\"cartfootercell_noborder\"></td><td class=\"cartfootercell\" colspan=\"2\"><b>Total:</b></td><td class=\"cartheadercell\"><b>\$$total</b></td></tr>" );

//			echo ( "<tr><td class=\"cartdivcell\" colspan=\"4\"></td></tr>" );


			// Paypal Form Start
			echo '<form action="https://www.paypal.com/us/cgi-bin/webscr" method="post">';
			echo '<input type="hidden" name="cmd" value="_cart"><input type="hidden" name="upload" value="1">';
			echo '<input type="hidden" name="business" value="j@jparkerstudio.com">';
			echo '<input type="hidden" name="no_shipping" value="0" />';

			$item_idx = 0;
			foreach ( $_SESSION['photo_id'] as $idx => $value )
			{
				$item_idx += 1;
				$product_info = $_SESSION['product_info'][$idx];
				$product_name = get_product_name( $product_info );
				echo "<input type=\"hidden\" name=\"item_number_$item_idx\" value=\"$product_name\">";

				$photo_idx = intval( $value );
				echo "<input type=\"hidden\" name=\"on0_$item_idx\" value=\"Photo_ID\">";
				echo "<input type=\"hidden\" name=\"os0_$item_idx\" value=\"$photo_idx\">";

				$gallery = $_SESSION['gallery_name'][$idx];
				echo "<input type=\"hidden\" name=\"on1_$item_idx\" value=\"Gallery Name\">";
				echo "<input type=\"hidden\" name=\"os1_$item_idx\" value=\"$gallery\">";

				if ( $handle = opendir('./images') ) 
				{
					$current_idx = 1; 
					while ( false !== ($file = readdir($handle)) ) 
					{
						if ( $file != "." && $file != ".." && get_file_extension($file) == "jpg" )
						{
							if ( $photo_idx == $current_idx )
							{
								echo "<input type=\"hidden\" name=\"item_name_$item_idx\" value=\"$file\">";
								break;
							}

							$current_idx += 1;
						}
					}
				}
				closedir( $handle );

				$quantity = $_SESSION['quantity'][$idx];
				echo "<input type=\"hidden\" name=\"quantity_$item_idx\" value=\"$quantity\">";

				$item_price = get_price( $product_info ) * 0.75;
				echo "<input type=\"hidden\" name=\"amount_$item_idx\" value=\"$item_price\">";
			}

			echo '<input type="hidden" name="return" value="http://www.jparkerstudio.com/gallery/2008/BealBooth/Gallery/">';
			echo '<input type="hidden" name="cancel_return" value="http://www.jparkerstudio.com/gallery/2008/BealBooth/Gallery/">';
			echo "<input type=\"hidden\" name=\"shipping\" value=\"$shipping\">";
			echo "<input type=\"hidden\" name=\"tax\" value=\"$tax\">";
			echo '<input type="hidden" name="currency_code" value="USD">';
			echo '<tr><td class="cartbuttoncell" colspan="4"><input type="submit" name="submit" class="cartsubmit" value="Check out using Paypal"></td></tr>';
			echo '</form>';
		}
		else
		{
			echo '<tr><td class="cartbuttoncell">Your cart is empty</td></tr></form>';
			echo "<tr><td class=\"cartbuttoncell\" colspan=\"3\"><a href=\"index.php?p=$page_number\">Continue Shopping</a></td></tr>";
		}
		
		echo( '</table></center></table><div id="secondarylogo"></div></div>' );
	?>	

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2468674-1";
urchinTracker();
</script>
</body>
</html>
