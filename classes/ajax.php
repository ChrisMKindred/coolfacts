<?php
/**
 * Cool_Facts_Ajax
 *
 * This class handles the ajax loading of new facts
 *
 * @package     Cool Facts
 * @since       1.0
 */

class Cool_Facts_Ajax {

	/**
	 * Get things started
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_random_quote', array( $this, 'ajax' ) );
		add_action( 'wp_ajax_nopriv_load_random_quote', array( $this, 'ajax' ) );
	}

	/**
	 * AJAX function that shows a new quote
	 *
	 * @access public
	 * @since 1.0
	 * @return string $output
	 */
	public function ajax() {

		$quotes = get_transient( CF_CACHE );
                $single = rand(0, sizeof( $quotes )-1);

		echo '<p id="coolwidgetquote">' . $quotes[$single] . '</p>';

		die();

	}

}
