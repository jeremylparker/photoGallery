<?php												


	function strip_extension( $str_name )
	{
		$ext = strrchr( $str_name, '.' );
		
		if ( $ext !== false )
			$str_name = substr( $str_name, 0, -strlen( $ext ) );
		
		return array( $str_name, $ext );
	}
	
	function create_thumbnail_name( $filename, $directory )
	{
	 	list( $name, $extension ) = strip_extension( $filename );
	 	$new_name = $directory.$name."_thumb".$extension;
	 	
	 	return $new_name;
	}

	function get_file_extension( $filename )
	{
		$path_info = pathinfo( $filename );
		return $path_info['extension'];
	}

	function convert_to_thumb( $filename, $directory )
	{
		$file_size = 135;
		list( $width, $height ) = getimagesize( $directory.$filename );
	 	if ( $width > $height )
	 	{
		 	$new_w = $file_size;
		 	$ratio = $height * $new_w; 
	 		$new_h = $ratio / $width; 
	 	}
	 	else
	 	{
		 	$new_h = $file_size;
		 	$ratio = $width * $new_h; 
	 		$new_w = $ratio / $height; 
	 	}
	 	$src_img = imagecreatefromjpeg( $directory.$filename );
	 	$dst_img = imagecreatetruecolor( $new_w,$new_h ); 
	 	imagealphablending( $dst_img, false );
	 	$source = @imagecreatefromjpeg( $directory.$filename );
	 	imagecopyresampled( $dst_img, $source, 0, 0, 0, 0, $new_w, $new_h, $width, $height ); 
	 	$new_file = create_thumbnail_name( $filename, $directory."thumbnails/" );
		imagejpeg( $dst_img, $new_file ); 

		return $new_file;
	}
?>
