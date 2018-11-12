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
	<title>JParker Studio Gallery - View Gallery</title>
	<LINK REL=StyleSheet HREF="/gallery/stylesheets/gallery_style.css" TYPE="text/css" MEDIA="all" />
</head>

<body>
	<?php
		$page_number = $_GET['p'];
		if ( !isset( $page_number ) )
			$page_number = 1;
		echo '<div id="cartbar"><div id="toptext">';
		$cart_quantity = writeShoppingCart();
		echo '</div><a href="/"><div id="toplogo"></div></a></div>';

		$file_handle = fopen( "gallery_helper.ini", "r" );
		$gallery_title = 0;
		if ( $file_handle )
		{
			$total_images = fgets( $file_handle ); // first is the img_count
			$gallery_title = fgets( $file_handle ); // second is the title
			fclose( $file_handle );
		}
		echo "<div id=\"mainbar\"><div id=\"titlebar\">$gallery_title</div>";
//		echo "<div id=\"announcement\">Early bird special: Order your prints before March 15, 2010 and receive 25% off! (discount reflected in shopping cart)</div>";
		echo ( '<table id="gallery" style="table-layout:fixed" cellspacing=15><tr>' );
		
		include ($_SERVER["DOCUMENT_ROOT"].'/gallery/include/thumb_convert.php');
		$cells_populated = 0;
		$max_cells = 4;
		$rows_populated = 0;
		$max_rows = 6;
		$num_to_skip = ($max_cells * $max_rows) * ($page_number - 1);
		$total_pages = ceil( $total_images / ($max_cells * $max_rows) );

		if ($handle = opendir('./images')) 
		{
			$current_idx = 0; // not set
			$last_page = false;
			$more_files_exist = false;
			$finished_populating = false;

    		while ( false !== ($file = readdir($handle)) ) 
			{
				if ( $file != "." && $file != ".." && get_file_extension( $file ) == "jpg" )
				{
					$more_files_exist = true;
					if ( $finished_populating )
						break;

					$current_idx += 1;
					if ( $num_to_skip == 0 )
					{
						//render this thumbnail
//					 	$thumb_image = create_thumbnail_name( $file, "images/thumbnails/" );
					 	$thumb_image = "images/thumbnails/$file";
					 	$link = "viewphoto.php?i=".$current_idx.'&p='.$page_number;
						echo ( "<td class=\"gallerycell\"><a href=$link><img class=\"pic\" style=\"position: relative; top: 0%; margin: 10px;\" src=$thumb_image /></a></td>" );
						++$cells_populated;

						if ( $cells_populated >= $max_cells )
						{
							echo( '</tr>' );
							$cells_populated = 0;
							++$rows_populated;
					
							if ( $rows_populated >= $max_rows )
							{
								$finished_populating = true;
							}
							else
							{
								// still not done so make a new row
								echo( '<tr>' );
							}
						}
					}
					else
					{
						//keep going
						--$num_to_skip;
					}
				}
				$more_files_exist = false;
    		}

			if ( !$more_files_exist && $cells_populated < $max_rows )
				echo '</tr><tr>';

			$prev_page = $page_number == 1 ? 1 : $page_number - 1;
			$next_page = $more_files_exist ? $page_number + 1: $page_number;

    		closedir($handle);
			echo( "<td class=\"navbutton_middle\"><a href=\"index.php?p=$prev_page\">Previous Page</a></td>" );
			
			echo( "<td class=\"navbutton_middle\" colspan=\"2\">Go to Page: " );

			$max_to_list = 10;
			$half_max = floor($max_to_list * 0.5);
			$max_to_follow = $total_pages - $page_number;
			if ( $max_to_follow > $half_max ) $max_to_follow = $half_max;
			$start_idx = ($page_number + $max_to_follow) - ($max_to_list - 1);
			if ( $start_idx < 1 ) $start_idx = 1;
			$amt_listed = 0;

			for ( $i=$start_idx; $i <= $total_pages && ($amt_listed < $max_to_list); ++$i, ++$amt_listed )
			{
				$printval = $amt_listed != ($max_to_list - 1) ? "$i | " : "$i";
				echo( "<a href=\"index.php?p=$i\">$printval</a>" );
			}
			echo( "</td><td class=\"navbutton_middle\"><a href=\"index.php?p=$next_page\">Next Page</a></td></tr>" );		
			echo( '</table><div id="secondarylogo"></div>' );
			echo( '<div id="footerbar"></div></div>' );
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
