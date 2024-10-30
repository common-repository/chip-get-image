<?php
/*
Plugin Name: Chip Get Image
Plugin URI: http://www.tutorialchip.com/chip-get-image/
Description: Chip Get Image is a WordPress Plugin. It is very flexible and easy to use plugin for adding thumbnails, featured images or other images to the blog posts.
Version: 0.3
License: GPLv2
Author: Life.Object
Author URI: http://www.tutorialchip.com/
*/

/**
* Initialization
*/

/**
* Add Thumbnail Support
* Required For chip_image_by_post_thumbnail
*/

add_theme_support( 'post-thumbnails' );

/**
* Public Methods
*/

/**
* CHIP Print
*/

function chip_get_print($var) {
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

/**
* CHIP Get Image Core Method
*/

function chip_get_image( $args = array() ){

	/**
	* Global Data
	*/
	
	global $post;	
	
	/**
	* Default Inputs
	*/
	
	$defaults = array(
		'post_id'				=>	$post->ID,
		'short_circuit'			=>	array( 'meta_key', 'the_post_thumbnail', 'attachment' ),
		'the_post_thumbnail'	=>	TRUE,
		'meta_key'				=>	array( 'Thumbnail', 'thumbnail' ),
		'attachment'			=>	TRUE,
		'size'					=>	'thumbnail',
		'attachment_order'		=>	1,
		'default_image'			=>	FALSE,
	);
	
	/**
	* Filter Hook for Plugins/Themes
	*/
	
	$args = apply_filters( 'get_the_image_args', $args );
	
	/**
	* Merge with Defaults
	*/	
	
	$args = wp_parse_args( $args, $defaults );
	
	/**
	* Get Variables
	*/	
	
	extract( $args );
	
	/**
	* Short Circuit Speedy Logic
	* Loop through Short Circuit
	*/
	
	$steptaken = 1;
	
	foreach( $short_circuit as $scan ) {
		
		$temp = chip_get_image_keys( $args, $scan );
		
		if ( !empty( $temp['val'] ) ) {
		
			$output = $temp['method']( $args );
			if ( !empty( $output ) ) {			
				$output['steptaken'] = $steptaken;
				$output['method'] = $temp['key'];
				break;
			}
			
		}
		
		++$steptaken;
		
	} // foreach( $short_circuit as $scan )
	
	/**
	* Default Image Logic
	*/
	
	if ( empty( $output ) && !empty($default_image) ) {
	
		/**
		* Process Output
		*/
		
		$output = array(
			'args'		=>	$args,
			'imageurl'	=>	$default_image,
			'method'	=>	'default_image',
			'steptaken'	=>	$steptaken,
			
		);
		
		$output = chip_get_image_output( $output );
		
	}	
	
	/**
	* A comprehensive output for processing
	*/
	
	return $output;	

}

/**
* Private Methods
*/

/**
* CHIP Get Image Keys
*/

function chip_get_image_keys( $args = array(), $key = "the_post_thumbnail" ) {
	
	$keys = array(
		
		"the_post_thumbnail"	=>	array( 
										"key"		=> "the_post_thumbnail",
										"val"		=> $args['the_post_thumbnail'],
										"method"	=> "chip_image_by_post_thumbnail"
									),
		
		"meta_key"				=>	array( 
										"key"		=> "meta_key",
										"val"		=> $args['meta_key'],
										"method"	=> "chip_image_by_meta_key"
									),
									
		"attachment"			=>	array( 
										"key"		=> "attachment",
										"val"		=> $args['attachment'],
										"method"	=> "chip_image_by_attachment"
									),		
	);
	
	return $keys[$key];

}

/**
* CHIP Get Image Output
*/

function chip_get_image_output( $args = array() ) {
	
	/**
	* Pre Default Processing
	*/
	
	$post_id = $args['args']['post_id'];
	$posturl = get_permalink( $post_id );
	$args['alt'] = ( ( !empty( $args['alt'] ) ) ? $args['alt'] : apply_filters( 'the_title', get_post_field( 'post_title', $post_id ) ) );
	
	/**
	* Default Inputs
	*/
	
	$defaults = array(
		'steptaken'			=>	FALSE,
		'method'			=>	FALSE,
		'imageurl'			=>	FALSE,
		'posturl'			=>	$posturl,		
		'alt'				=>	$args['alt'],
		'post_thumbnail_id'	=>	FALSE,
		'args'				=>	FALSE,
		
	);
	
	/**
	* Merge with Defaults
	*/	
	
	return wp_parse_args( $args, $defaults );
	
}

/**
* CHIP Get Image by Meta Key
*/


function chip_image_by_meta_key( $args = array() ) {

	/**
	* If Meta Key is not an array
	*/
	
	if ( !is_array( $args['meta_key'] ) ) {

		/**
		* Get Image by the single meta key
		*/
		
		$imageurl = get_post_meta( $args['post_id'], $args['meta_key'], true );
	}
	
	
	/**
	* If Meta Key is an array
	*/

	else if ( is_array( $args['meta_key'] ) ) {

		/**
		* Loop of Meta Key array
		*/
		
		foreach ( $args['meta_key'] as $meta_key ) {

			$imageurl = get_post_meta( $args['post_id'], $meta_key, true );
			if ( !empty( $image ) ) {
				break;
			}
		
		} // foreach ( $args['meta_key'] as $meta_key )
	
	} // else if ( is_array( $args['meta_key'] ) )

	/**
	* Image Found
	*/
	
	if ( !empty( $imageurl ) ) {
		
		/**
		* Process Output
		*/
		
		$output = array(
			"args"		=>	$args,
			"imageurl"	=>	$imageurl,
		);
		
		return chip_get_image_output( $output );
	
	}

	/**
	* Image Not Found
	*/	
	
	return FALSE;
}

/**
* CHIP Get Image by Post Thumbnail
*/

function chip_image_by_post_thumbnail( $args = array() ) {

	/**
	* Check for Post Image ID
	*/
	
	$post_thumbnail_id = get_post_thumbnail_id( $args['post_id'] );
	if ( empty( $post_thumbnail_id ) ) {
		
		/**
		* Image Not Found
		*/
		
		return FALSE;
	}

	
	/**
	* Image Found
	*/
	
	/**
	* Apply Filters
	*/
	
	$size = apply_filters( 'post_thumbnail_size', $args['size'] );

	/**
	* Get Attachment Image return array()
	*/
	
	$image = wp_get_attachment_image_src( $post_thumbnail_id, $size );
	
	/**
	* Post Excerpt
	*/

	$alt = trim( strip_tags( get_post_field( 'post_excerpt', $post_thumbnail_id ) ) );
	
	/**
	* Process Output
	*/
	
	$output = array(
		"args"				=>	$args,
		"imageurl"			=>	$image[0],
		'post_thumbnail_id'	=>	$post_thumbnail_id,
		'alt'				=>	$alt,
	);
	
	return chip_get_image_output( $output );
}

/**
* CHIP Get Image by Attachment
*/

function chip_image_by_attachment( $args = array() ) {

	/**
	* Query Attachment
	*/
	
	$attachments = get_children( array( 'post_parent' => $args['post_id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) );

	/**
	* Image Not Found
	*/	
	
	if ( empty( $attachments ) ) {
		return FALSE;
	}
	
	/**
	* Image Found
	*/

	/**
	* Get Attachment Image Independant Choice
	*/
	
	$i = 1;

	foreach ( $attachments as $id => $attachment ) {
		
		if ( $i == $args['attachment_order'] ) {
			$image = wp_get_attachment_image_src( $id, $args['size'] );
			$alt = trim( strip_tags( get_post_field( 'post_excerpt', $id ) ) );
			break;
		}
		
		++$i;
	}
	
	/**
	* Process Output
	*/
	
	$output = array(
		"args"				=>	$args,
		"imageurl"			=>	$image[0],
		'alt'				=>	$alt,
	);
	
	return chip_get_image_output( $output );

}


?>