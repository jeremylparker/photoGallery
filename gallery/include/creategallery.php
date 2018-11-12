<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Jeremy Parker Photography Create Gallery</title>

	<style type = "text/css" media = "screen">
	body
	{
		margin: 0px 0px 0px 0px;
		background: #6e8f97;
		font-family: Georgia, Trebuchet, Verdana, Arial, Helvetica, sans-serif;
	}
			
	#topbar
	{
		position: absolute;
		top: 50%;
		height: 15px;
		background: transparent;

		letter-spacing: 5px;
 	    margin-top:  -287px;
		margin-left:  12.5%;
		font-size: 14px;
		color: #fff;
	}
	
	#topbar a, #topbar a:visited
	{
		color: #fff;
		background-color: #6e8f97;
		text-decoration: none;
		border-bottom: 0px;
	}

	#topbar a:hover, #topbar a:active
	{
		color: #fff;
		background-color: #6e8f97;
		text-decoration: none;
		border-bottom: 0px;
	}
	
	.photoclass
	{
		position: relative;
		border: 2px solid #ceeff7;
		top: 40px;
	}
	
	#mainbar 
	{
		position: absolute;
		margin-top:  -267px; 
		margin-left: 12.5%;
		top: 50%;
		width: 75%;
		border-top: 2px solid #ceeff7;
		border-right: 2px solid #ceeff7;
		border-left: 2px solid #ceeff7;
		background: #8eafb7;
	}
	
	#commentbar
	{
		position: relative;
		top: 45px;
		left: 0%;
		width: 100%;
		background: transparent;

		text-align: center;
		font-size: 12px;
		color: #fff;
	}
	
	#copyrightbar
	{
		position: absolute;
		bottom: 4px;
		left: 0%;
		width: 100%;
		margin-top: 287px;
		background: transparent;

		text-align: center;
		font-size: 11px;
		letter-spacing: 2px;
		color: #000000;
	}
		
	#navbar
	{
		position: absolute;
		margin-top:  -133.5px;
		margin-left: 345px;
		top: 50%;
		left: 50%;
		text-align: left;
		padding-top: 40px;
		background: transparent;
		line-height: 2;
		color: #6e8f97;
		font-size: 13px;
	}

	#navbar a, #navbar a:visited
	{
		color: #6e8f97;
		background-color: #ceeff7;
		text-decoration: none;
		border-bottom: 0px;
	}

	#navbar a:hover, #navbar a:active
	{
		color: #8eafb7;
		background-color: #ceeff7;
		text-decoration: none;
		border-bottom: 0px;
	}
	
	table#gallery
	{
		width: 100%;
		overflow: hidden;
	}
		
	.gallerycell
	{
		width: 25%;
		overflow: hidden;
		text-align: center;
		background-color: #8eafb7;
		border: 2px solid #ceeff7;
	}

	.pic
	{
		border: 1px solid #000;
	}
	
	.navbutton_left
	{
		border: 0;
		color: #fff;
		text-align: left;
		vertical-align: bottom;
		padding-top: 15px;
	}

	.navbutton_middle
	{
		border: 0;
		color: #fff;
		text-align: center;
		vertical-align: bottom;
		padding-top: 15px;
	}
	
	.navbutton_right
	{
		border: 0;
		color: #fff;
		text-align: right;
		vertical-align: bottom;
		padding-top: 15px;
	}
	
	</style>
</head>
<body>
	<?php												

		if ( $handle = opendir('./images/') ) 
		{
			include 'thumb_convert.php';

			$percent = 0.25;
			echo "<table align=\"center\">";
			$path = "./images/thumbnails";

			if( !is_dir($path) )
			{
				echo "<tr align=center bgcolor=#fff>";
				echo "<td align=\"center\" colspan=2>";
				echo "Creating the thumbnail directory...</td></tr>";

				if( mkdir($path, 0711) )
				{
					echo "<tr align=center bgcolor=#fff>";
					echo "<td align=\"center\" colspan=2>";
					echo "The thumbnail directory was created successfully.</td></tr>";
				}
				else
					die ("Error: There was an error while creating the thumbnail directory. Aborting.");
			}

			$num_images = 0;
			while ( false !== ($file = readdir($handle)) ) 
			{
				if ( $file != "." && $file != ".." && get_file_extension( $file ) == "jpg" ) 
				{
					$new_file = convert_to_thumb( $file, "images/" );
					$num_images += 1;

					// display the resulting thumbnail
					echo "<tr align=center bgcolor=#fff>";
					echo "<td align=\"center\">";
					echo "Created thumbnail for ".$file."\n\n";

					echo "</td><td>";
					echo "<img src=$new_file>";
					echo "</td></tr>";
				}
			}

			$file_handle = fopen( "gallery_helper.ini", "w" );
			fwrite( $file_handle, "$num_images" );
			fclose( $file_handle );

			echo "<tr align=center bgcolor=#fff>";
			echo "<td align=\"center\" colspan=2>";
			echo "All thumbnails have been generated successfully.\n</td></tr></table></body></html>";
			closedir($handle);
		}	

	
	
	
// echo "<tr align=center bgcolor=#fff onMouseOver=\"this.bgColor='#33333';\" onMouseOut=\"this.bgColor='#1A1A1A';\">";
	?>	
</body>
</html>


