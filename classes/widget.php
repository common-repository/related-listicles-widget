<?php
/**
 * The custom Listicor Widget.
 * This widget gives total control over the output to the user.
 *
 * @package    Related_Listicles_Widget
 * @since      0.5
 * @author     Relevad
 * @copyright  Copyright (c) 2016, Relevad
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Related_Listicles_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 0.5
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			//'classname'   => 'lp_widget',
			//NOTE: classname is populated with   'widget_' . $this->id_base   by default
			'description' => __( 'Displays posts from Listicor search engine in a configurable widget.', 'related-listicles-widget' )
		);

		$control_options = array(
			'width'  => 750,
			'height' => 350 //NOTE never used
			//NOTE: id_base is populated with ( empty($id_base) ? preg_replace( '/(wp_)?widget_/', '', strtolower(get_class($this)) ) : strtolower($id_base) )
			//      by default
		);

		/* Create the widget. */
		parent::__construct(
			//NOTE: if this changes it invalidates all existing instances of this widget in this wordpress install
			'rlw_widget',                                        // $this->id_base
			__( 'Listicor Widget', 'related-listicles-widget' ), // $this->name
			$widget_options,                                     // $this->widget_options
			$control_options                                     // $this->control_options
		);
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.5
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$listicor_posts = rlw_get_recent_posts( $instance );

		if ( $listicor_posts ) {

			// Output the sidebar's $before_widget wrapper.
			echo $before_widget;

			// If there is a title
			if ( !empty($instance['title']) ) {
				$title = apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base );
				// If there is a title URL
				if ( !empty($instance['title_url'] ) )  {
					$title = '<a href="' . esc_url( $instance['title_url'] ) . '" title="' . esc_attr( $instance['title'] ) . '">' . $title . '</a>';
				}
				// Print the constructed title
				echo "{$before_title}{$title}{$after_title}";
			}

			// Get the Listicor Posts query.
			echo $listicor_posts;

			// Close the sidebar's widget wrapper.
			echo $after_widget;
		}
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.5
	 *
	 * returns object - the updated widget instance
	 */
	function update( $new_instance, $old_instance ) {
		//NOTE: redundantly escaping/sanitizing values here... AND when writing into the "inside widget area"
		$instance			= $old_instance;
		$instance['title']		= strip_tags(   $new_instance['title'] );            //text
		$instance['title_url']		= esc_url(      $new_instance['title_url'] );        //text
		$instance['keyword']		=               $new_instance['keyword'];            //text
		$instance['limit']		= min((int)(    $new_instance['limit'] ), 20);       //number
		$instance['offset']		= min((int)(    $new_instance['offset']), 100);      //number
		$instance['order']		= stripslashes( $new_instance['order'] );            //dropdown --TODO: why is stripslashes() necessary?
		$instance['orderby']		= stripslashes( $new_instance['orderby'] );          //dropdown
		$instance['date']		= isset(        $new_instance['date'] )          ? (bool) $new_instance['date']          : false; //checkbox
		$instance['date_relative']	= isset(        $new_instance['date_relative'] ) ? (bool) $new_instance['date_relative'] : false; //checkbox
		$instance['thumb']		= isset(        $new_instance['thumb'] )         ? (bool) $new_instance['thumb']         : false; //checkbox
		$instance['thumb_default']	= esc_url(      $new_instance['thumb_default'] );    //text
		$instance['thumb_align']	= esc_attr(     $new_instance['thumb_align'] );      //dropdown
		$instance['style_preset']	= stripslashes( $new_instance['style_preset'] );     //dropdown
		$instance['cssID']		= sanitize_html_class( $new_instance['cssID'] );     //text
		$instance['css_class']		= sanitize_html_class( $new_instance['css_class'] ); //text
		$instance['css']		=               $new_instance['css']; //extra css styling textarea

		$instance['excerpt']            = isset(        $new_instance['excerpt'] )  ? (bool) $new_instance['excerpt']  : false;	//NOTE: disabled
		$instance['length']             = (int)(        $new_instance['length'] );						//NOTE: disabled
		$instance['readmore']           = isset(        $new_instance['readmore'] ) ? (bool) $new_instance['readmore'] : false;	//NOTE: disabled
		$instance['readmore_text']      = strip_tags(   $new_instance['readmore_text'] );					//NOTE: disabled

		//NOTE: handles raw number and numbers with 'px' or '%'
		//TODO: change to use a regex after stripping spaces
		if ( strrpos($new_instance['thumb_width'], 'px', -2) || strrpos($new_instance['thumb_width'], '%', -1) ) $instance['thumb_width']  = $new_instance['thumb_width'];
		if ( strrpos($new_instance['thumb_height'],'px', -2) || strrpos($new_instance['thumb_height'],'%', -1) ) $instance['thumb_height'] = $new_instance['thumb_height'];

		//return false; //NOTE: returning false means it won't update.... but it will NOT give any error to the client, so pretty useless 
		//it will return the default instance though, which is even dumber.
		//all real validation would have to occur in javascript if I want realisitic error messages for the user
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.5
	 */
	function form( $instance ) {

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, rlw_get_default_args() );

		// Extract the array to allow easy use of variables.
		extract( $instance );

		// Loads the widget form.
		//NOTE: separate file because its mostly HTML syntax instead of php
		include( rlw_INCLUDES . 'form.php' );
	}
}
