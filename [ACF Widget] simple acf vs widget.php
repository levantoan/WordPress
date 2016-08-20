<?php
/*Add Tips widget*/
class Tips_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'tips_widget',
			'description' => 'View title, image and link to page',
		);
		parent::__construct( 'tips_widget', 'OG Image widget', $widget_ops );
	}
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$widgetID = 'widget_'.$args['widget_id'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		//ACF field here
		echo get_field('test_field',$widgetID);
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}
function register_tips_widget() {
    register_widget( 'Tips_Widget' );
}
add_action( 'widgets_init', 'register_tips_widget' );
