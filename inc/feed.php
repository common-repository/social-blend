<?php

// exit if accessed directly
defined( 'ABSPATH' ) or die;

function_exists( 'add_action' ) or die;

add_action( 'wp_enqueue_scripts', 'socialblend_scripts', 0 );
function socialblend_scripts() {
	wp_register_script(
		'socialblendfeed',
		'https://cdn.socialblend.com/assets/app/socialblend.js',
		array(),
		false,
		true
	);
}

/**
 * Social Blend Feed class.
 *
 * @class SocialBlendFeed
 * @version	1.0
 */
final class SocialBlendFeed {

	public function render( $args ) {

		$powered_by = get_option( 'socialblend_fields_poweredby' ); 
		
		$defaults = array(
		  'id' => 'id is required',
		);
	
		$attributes = wp_parse_args($args, $defaults);

		wp_enqueue_script('socialblendfeed');
	
		$markup = "<div id='socialblend'>";
		$markup .= "\t<feed ";
		foreach($attributes as $key => $value)
		{
			$markup .= " " . $key . '="' . $value . '"'; 
    	}
		$markup .= " />\n";
		$markup .= "</div>";
		if($powered_by)
		{
			$url = "https://socialblend.com/"; 
			if(array_key_exists("id", $attributes)) { $url .= "?referrer=" . $attributes['id']; }
			$markup .= "\t<div class='sb-poweredby-container'>\n";
			$markup .= "\t\t<a href='" . $url . "' target='_blank'>Powered by socialblend.com</a>";
			$markup .= "\t</div>";
		}
		
		return $markup;
	}
}