<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php 
		if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
		  $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(
			$_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );

		include ($_SERVER["DOCUMENT_ROOT"].'/gallery/include/carthelper.php');
		session_start();
?>

<head>
	<title>JParker Studio Gallery - View Photo</title>
	<LINK REL=StyleSheet HREF="/gallery/stylesheets/gallery_style.css" TYPE="text/css" MEDIA="all" />
    <script type="text/javascript" src="/gallery/scripts/keyboardScript.js"></script>
</head>

<body>
	<?php
		$page_number = $_GET['p'];
		if ( !isset( $page_number ) )
			$page_number = 0;

		echo '<div id="cartbar"><div id="toptext">';
		$cart_quantity = writeShoppingCart();
		echo '</div><a href="/"><div id="toplogo"></div></a></div>';
		echo "<div id=\"mainbar\">";
		echo ( '<tr><center><table id="gallery" cellspacing=15>' );
		
		$img_idx = $_GET['i'];
		if ( !isset( $img_idx ) )
			$img_idx = 0;
			
		include ($_SERVER["DOCUMENT_ROOT"].'/gallery/include/thumb_convert.php');
		if ($handle = opendir('./images')) 
		{
			$current_idx = 1; 
    		while ( false !== ($file = readdir($handle)) ) 
			{
				if ( $file != "." && $file != ".." && get_file_extension($file) == "jpg" )
				{
					if ( $img_idx == $current_idx )
					{
						$file_handle = fopen( "gallery_helper.ini", "r" );
						$total_images = 0;
						if ( $file_handle )
						{
							$total_images = fgets( $file_handle );
							fclose( $file_handle );
						}

						//render this image
						$next_img = $current_idx + 1;
						if ( $next_img > $total_images ) $next_img = $total_images;
						$prev_img = $current_idx - 1;
						if ( $prev_img <= 0 ) $prev_img = 1;

						echo ( '<td class="gallerycellheader"><table id="gallery" cellspacing=0><tr>' );
						echo ( "<tr><td class=\"internalcellL\"><a href=\"viewphoto.php?i=$prev_img&p=$page_number\">Previous Photo </a><br />(Left Arrow Key)</td><td class=\"internalcellM\"><a href=\"index.php?p=$page_number\">Back to Gallery</a></td><td class=\"internalcellR\"><a href=\"viewphoto.php?i=$next_img&p=$page_number\">Next Photo</a><br />(Right Arrow Key)</td></tr>" );
						echo ( "<td class=\"photocell\" colspan=\"3\"><img class=\"pic\" src=\"images/$file\" /></td>" );
						echo ( "<tr><td class=\"internalcellL\" colspan=\"2\">ID: $file</td><td class=\"internalcellR\">Image $img_idx of $total_images</td></tr>" );
						echo ( '</table></td>' );

						//render buying options
						echo ( '<td class="itemcell"><center><table id="item_options"' );
						echo ( '<tr><td class="internalcellL" colspan="3"><span style="font-size: 12px">An asterisk denotes a full-frame image</td></tr>' );
						echo ( '<tr><td class="gallerycell">Quantity</td>' );
						echo ( '<td class="gallerycell">Item</td>' );
						echo ( '<td class="gallerycell">Price</td>' );

						echo ( "<form method=\"post\" action=\"viewcart.php?p=$page_number\">" );

						//send juicy bits of hidden info
						$file_handle = fopen( "gallery_helper.ini", "r" );
						$gallery_title = "Error: Unknown Gallery";
						if ( $file_handle )
						{
							$gallery_title = fgets( $file_handle ); // first is the img_count
							$gallery_title = fgets( $file_handle ); // second is the title
							fclose( $file_handle );
						}
						echo ( "<input type=\"hidden\" name=\"gallery_name\" value=\"$gallery_title\"/>" );
						echo ( "<input type=\"hidden\" name=\"photo_id\" value=\"$img_idx\"/>" );
						echo ( "<input type=\"hidden\" name=\"addtocart\" value=\"1\" />" );

						// 4 x 6
						echo ( '<tr><td class="gallerycell"><select name="4x6_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*4 x 6</td>' );
						echo ( '<td class="gallerycell">$25.00</td>' );
						echo ( '</tr>' );

						// 5 x 7
						echo ( '<tr><td class="gallerycell"><select name="5x7_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">5 x 7</td>' );
						echo ( '<td class="gallerycell">$35.00</td>' );
						echo ( '</tr>' );

						// 8 x 10
						echo ( '<tr><td class="gallerycell"><select name="8x10_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">8 x 10</td>' );
						echo ( '<td class="gallerycell">$50.00</td>' );
						echo ( '</tr>' );

						// 8 x 12
						echo ( '<tr><td class="gallerycell"><select name="8x12_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*8 x 12</td>' );
						echo ( '<td class="gallerycell">$55.00</td>' );
						echo ( '</tr>' );

						// 11 x 14
						echo ( '<tr><td class="gallerycell"><select name="11x14_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">11 x 14</td>' );
						echo ( '<td class="gallerycell">$100.00</td>' );
						echo ( '</tr>' );

						// 12 x 18
						echo ( '<tr><td class="gallerycell"><select name="12x18_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*12 x 18</td>' );
						echo ( '<td class="gallerycell">$150.00</td>' );
						echo ( '</tr>' );

						// 16 x 24
						echo ( '<tr><td class="gallerycell"><select name="16x24_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*16 x 24</td>' );
						echo ( '<td class="gallerycell">$225.00</td>' );
						echo ( '</tr>' );

						// 20 x 30
						echo ( '<tr><td class="gallerycell"><select name="20x30_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*20 x 30</td>' );
						echo ( '<td class="gallerycell">$300.00</td>' );
						echo ( '</tr>' );

						// 24 x 36
						echo ( '<tr><td class="gallerycell"><select name="24x36_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*24 x 36</td>' );
						echo ( '<td class="gallerycell">$375.00</td>' );
						echo ( '</tr>' );

						// 30 x 40
						echo ( '<tr><td class="gallerycell"><select name="30x40_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">30 x 40</td>' );
						echo ( '<td class="gallerycell">$500.00</td>' );
						echo ( '</tr>' );

						// 30 x 45
						echo ( '<tr><td class="gallerycell"><select name="30x45_quantity"> ' );
						for ( $i = 0; $i < 10; $i++ )
							echo ( "<option value=\"$i\">$i</option>" );
						echo ( '</select></td>' );
						echo ( '<td class="gallerycell">*30 x 45</td>' );
						echo ( '<td class="gallerycell">$550.00</td>' );
						echo ( '</tr>' );
						
						echo ( '<tr><td class="cartbuttoncell" border="0" colspan="3"><input type="submit" name="submit" class="cartsubmit" value="Add to Cart"/></td></tr></table></tr>' );
						break;
					}
					else
					{
						//keep going
						$current_idx += 1;
					}
				}
    		}
    		closedir($handle);
			echo( '</table></center></table><div id="secondarylogo"></div></div>' );
		}
	?>	

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2468674-1";
urchinTracker();
</script>
</body>
</html>
