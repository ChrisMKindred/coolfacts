<?php
/*
Plugin Name: Cool Facts
Plugin URI: http://github.com/chrismccoy/coolfacts
Description: Displays a cool fact on your WordPress Powered Site
Version: 1.0
Author: Chris McCoy
Author URI: http://github.com/chrismccoy
Text Domain: coolfacts
Domain Path: /lang/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Cool_Facts {

	/**
	 * @var Cool_Facts
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * @var Class Globals
	 */

	var $version = '1.0';
	var $lang = 'coolfacts';
	var $cache_id = '_cool_facts';

	/**
	 * Main Cool_Facts Instance
	 *
	 * Insures that only one instance of Cool_Facts exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @staticvar array $instance
	 * @return The highlander Cool_Facts
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Cool_Facts ) ) {
			self::$instance = new Cool_Facts;
			self::$instance->setup_constants();
			self::$instance->includes();

			register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );

			add_action( 'init', array( self::$instance, 'init' ), 5 );
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'display_js' ) );

			add_filter( 'plugin_row_meta', array( self::$instance, 'add_plugin_row_meta'), 10, 4 );

			add_action( 'widgets_init', array( self::$instance, 'register_widget' ) );

			add_filter( 'widget_text', 'do_shortcode' );

			add_action( 'plugins_loaded', array( self::$instance, 'load_lang' ) );
		}

		return self::$instance;
	}

	public function init() {
		self::$instance->ajax = new Cool_Facts_Ajax();
		self::$instance->shortcode = new Cool_Facts_Shortcode();
		self::$instance->widget = new Cool_Facts_Widget();

	}

	/**
	 * Add our language files.
	 * Load the text domain
	 *
	 * @since 1.0.4
	 * @access public
	 * @return void/
	 */
	public function load_lang() {

		/** Set our unique textdomain string */
		$textdomain = CF_LANG;

		/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
		$locale = apply_filters( 'plugin_locale', get_locale(), $textdomain );

		/** Set filter for WordPress languages directory */
		$wp_lang_dir = apply_filters(
			'cool_facts_wp_lang_dir',
			WP_LANG_DIR . '/coolfacts/' . $textdomain . '-' . $locale . '.mo'
		);

		/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
		load_textdomain( $textdomain, $wp_lang_dir );

		/** Translations: Secondly, look in plugin's "lang" folder = default */
		$plugin_dir = basename( dirname( __FILE__ ) );
		$lang_dir = apply_filters( 'cool_facts_lang_dir', $plugin_dir . '/lang/' );
		load_plugin_textdomain( $textdomain, FALSE, $lang_dir );

	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', CF_LAND ), '1.0' );
	}

	/**
	 * Register the Cool Facts Widget
	 *
	 * @since 1.0
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'Cool_Facts_Widget' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', CF_LANG ), '1.0' );
	}

	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function setup_constants() {
		global $wpdb;

		// Plugin version
		if ( ! defined( 'CF_PLUGIN_VERSION' ) ) {
			define( 'CF_PLUGIN_VERSION', '1.0' );
		}

		// Plugin Folder Path
		if ( ! defined( 'CF_PLUGIN_DIR' ) ) {
			define( 'CF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'CF_PLUGIN_URL' ) ) {
			define( 'CF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File
		if ( ! defined( 'CF_PLUGIN_FILE' ) ) {
			define( 'CF_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Language text domain
		if ( ! defined( 'CF_LANG' ) ) {
			define( 'CF_LANG', self::$instance->lang );
		}

		// Plugin Language text domain
		if ( ! defined( 'CF_CACHE' ) ) {
			define( 'CF_CACHE', self::$instance->cache_id );
		}

	}

	/**
	 * Include our Class files
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function includes() {
		require_once( CF_PLUGIN_DIR . 'classes/ajax.php' );
		require_once( CF_PLUGIN_DIR . 'classes/shortcode.php' );
		require_once( CF_PLUGIN_DIR . 'classes/widget.php' );
	}

	/**
	 * Sets transient for cached quotes
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function activation() {

		if ( false === ( $quotes = get_transient( CF_CACHE ) ) ) {
  			$quotes = file(CF_PLUGIN_DIR . 'inc/quotes.txt');
     			set_transient( CF_CACHE, $quotes, YEAR_IN_SECONDS );
		}

	}

	/**
	 * Enqueue our display (front-end) JS
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function display_js() {
		wp_enqueue_script( 'coolfacts', CF_PLUGIN_URL .'assets/js/coolfacts.js', array( 'jquery' ) );
		wp_localize_script( 'coolfacts', 'Ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

 	/**
	 * @param $plugin_meta
	 * @param $plugin_file
	 * @param $plugin_data
	 * @param $status
	 *
	 * @return array
	 */
	public function add_plugin_row_meta ( $plugin_meta, $plugin_file) {

		$plugin_slug =  plugin_basename(__FILE__);

		if ( $plugin_slug == $plugin_file ) {
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://github.com/chrismccoy/', CF_LANG ), __( 'GitHub', CF_LANG ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://twitter.com/chrismccoy/', CF_LANG ), __( 'Twitter', CF_LANG ) );
		}

		return $plugin_meta;
	}

} // End Class

/**
 * The main function responsible for returning the one true Cool_Facts
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $cf = Cool_Facts(); ?>
 *
 * @since 1.0
 * @return object The highlander Cool_Facts Instance
 */
function Cool_Facts() {
	return Cool_Facts::instance();
}

// Get Cool_Facts Running
Cool_Facts();
