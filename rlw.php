<?php
/*
 * Plugin Name:  Listicor Widget
 * Plugin URI:   https://wordpress.org/plugins/related-listicles-widget/
 * Description:  Shows keyword related posts from Listicor.com a listicles search engine. Based on "Related Posts Widget Extended" by Satrya
 * Version:      0.5.1
 * Author:       Relevad
 * Author URI:   http://relevad.com/
 * Text Domain:  related-listicles-widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//NOTE: RLW instead of Related_Listicles_Widget as that is defined in an include file
class RLW {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  0.5
	 */
	public function __construct() {

		/* =======================================
		   Define all the constants we'll need everywhere else in the plugin
		   ======================================== */

		// this file - possibly used within other include files for activation hook
		define( 'rlw_main_file', __FILE__);

		// Path to the plugin directory.
		define( 'rlw_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		// Path to the plugin directory URI.
		define( 'rlw_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		// Path to the includes directory.
		define( 'rlw_INCLUDES', rlw_DIR . trailingslashit( 'includes' ) );

		// Path to the includes directory.
		define( 'rlw_CLASS', rlw_DIR . trailingslashit( 'classes' ) );

		// Path to the assets directory.
		define( 'rlw_ASSETS', rlw_URI . trailingslashit( 'assets' ) );


		// Load the functions files.
		//add_action( 'plugins_loaded',        array( &$this, 'includes' ), 3 );
		$this->includes(); //why delay? -- need this now for dropin

		// Internationalize the text strings used.
		add_action( 'plugins_loaded',        array( &$this, 'i18n' ), 2 );

		// Load the admin style.
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_style' ) );

		// Load our pixel
		add_action('wp_enqueue_scripts',     array( &$this, 'scripts_enqueue' ) );

		// Register widget.
		add_action( 'widgets_init',          array( &$this, 'register_widget' ) );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.5
	 */
	public function i18n() {
		load_plugin_textdomain( 'related-listicles-widget', false, rlw_DIR . 'languages/' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.5
	 */
	public function includes() {
		require_once( rlw_INCLUDES . 'listicor_api_include.php' ); // used in functions.php
		require_once( rlw_INCLUDES . 'functions.php' );
		require_once( rlw_INCLUDES . 'shortcode.php' );
	}

	/**
	 * Register custom style for the widget settings.
	 *
	 * @since  0.5
	 */
	public function admin_style() {
		// Loads the widget style.
		wp_enqueue_style( 'rlw-admin-style', rlw_ASSETS . 'css/rlw-admin.css', null, null );
	}

	/**
	 * Enqueues our tracking pixel
	 *
	 * @since  0.5
	 */
	public function scripts_enqueue() {
		//skip admin pages or any ssl pages
		if (is_admin() || defined('DOCROOT') || is_ssl()) { return; }

		//NOTE: does some encoding of & into &#038; ... why? dunno... but doesn't seem to cause problems
		//wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script('ipq', "http://websking.com/static/js/ipq.js?ft=rlw", array(), null, false);
	}

	/**
	 * Register the widget.
	 *
	 * @since  0.5
	 */
	public function register_widget() {
		require_once( rlw_CLASS . 'widget.php' );
		register_widget( 'Related_Listicles_Widget' );
	}
}

new RLW;

//if you have custom code to modify behavior of this plugin, include it in this file
if (file_exists(  rlw_DIR . 'rlw_dropin.php')) {  
    include_once( rlw_DIR . 'rlw_dropin.php');
} 

