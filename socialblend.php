<?php
/*
Plugin Name: Social Blend
Description: Social Media Aggregator Social Blend allows website owners to blend together all their brand hashtags and social media posts into a single beautiful feed.
Version: 1.0
Author: socialblend.com
Author URI: https://socialblend.com
Plugin URI: http://wordpress.org/extend/plugins/social-blend/
License: GPLv2 or later

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// exit if accessed directly
defined( 'ABSPATH' ) or die;

function_exists( 'add_action' ) or die;

class SocialBlendPlugin {

	private $settings;

	public function __construct() {
		
		require_once dirname( __FILE__ ) . '/inc/settings.php';
		require_once dirname( __FILE__ ) . '/inc/feed.php';

		if ( is_admin() ) { 
			$this->settings = new SocialBlendSettings();
		}
	}

	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new SocialBlendPlugin();
		}

		return $instance;
	}

}

//$GLOBALS['SocialBlendPlugin'] = 
SocialBlendPlugin::instance();

function socialblend_feed( $args ) {
    $feed = new SocialBlendFeed();
    echo $feed->render( $args );
}

function socialblend_shortcode( $args ) {
	$feed = new SocialBlendFeed();
	return $feed->render( $args );
}

add_shortcode( 'socialblend', 'socialblend_shortcode' );
