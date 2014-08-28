<?php
/**
 * Cool_Facts_Shortcode
 *
 * This class handles outputting of our shortcodes:
 *
 * @package     Cool Facts
 * @since       1.0
 */

class Cool_Facts_Shortcode {

	/**
	 * Get things started
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function __construct() {
		add_shortcode( 'coolfacts', array( $this, 'shortcode' ) );

	}

	/**
	 * Shortcode function that outputs a button for users to try the demo/create a new sandbox.
	 *
	 * @access public
	 * @since 1.0
	 * @return string $output
	 */
	public function shortcode( $atts ) {

		$quotes = get_transient( CF_CACHE );

		$single = rand(0, sizeof( $quotes )-1);

		$content = '<p id="coolquote">' . $quotes[$single] . '</p>';

		return $content; 

	}

}
