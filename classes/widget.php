<?php
/**
 * Adds Cool Facts widget.
 */
class Cool_Facts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'cool_facts_widget', // Base ID
			'Cool Facts Widget', // Name
			array( 'description' => __( 'Cool Facts Widget', CF_LANG ), ) // Args
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $title );

		echo $before_widget;
		if ( ! empty( $title ))
			echo $before_title . $title . $after_title;


		$quotes = get_transient( CF_CACHE );

		$single = rand(0, sizeof( $quotes)-1);

		echo '<p id="coolwidgetquote">' . $quotes[$single] . '</p>';
		echo '<a href="#" id="anotherwidgetquote">refresh fact</a>';

		echo $after_widget;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';

		?>
		<p>
			<label>
				<?php _e( 'Title', CF_LANG ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
			</label>
		</p>
		<?php
	}

} 

