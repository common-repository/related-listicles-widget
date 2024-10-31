<?php
/**
 * Display Listicor Widget via shortcode.
 *  
 * @package    Related_Listicles_Widget
 * @since      0.5
 * @author     Relevad
 * @copyright  Copyright (c) 2016, Relevad
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//Example Shortcode: [rlw  cssID="foobar" limit="10" keyword="sports" orderby="title" ]
//reference rlw_get_default_args() in include/functions.php for a complete list
//TODO: make a complete list here of all valid options, things like "title" don't work because those are widget/sidebar specific

/*
 * Creates a LPW based on shortcode params
 * @since  0.5
 *
 * returns string - the widget html
 */
function rlw_shortcode( $atts, $content ) {
	$args = shortcode_atts( rlw_get_default_args(), $atts );
	return rlw_get_recent_posts( $args );
}
add_shortcode( 'rlw', 'rlw_shortcode' );
