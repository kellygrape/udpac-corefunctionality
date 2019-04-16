<?php
/**
 * Widgets
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
Plugin Name: Shows Listing Widget for Summer Stage
Description: UDPAC Widgets
Version: 1.0
Author: Kelly Anne Pipe

1) Production Sponsor Widget
2) Production Tickets Widget (Tickets Area)
3) Register sidebars and widgets
*/

// Register and load the widget
function udpac_summerstage_shows_load_widget() {
    register_widget( 'udpac_summerstage_shows_widget' );
}
add_action( 'widgets_init', 'udpac_summerstage_shows_load_widget' );

// Creating the widget
class udpac_summerstage_shows_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            // Base ID of your widget
            'udpac_summerstage_shows_widget_id',
            // Widget name will appear in UI
            __('Summer Shows', 'udpac'),
            // Widget description
            array( 'description' => __( 'Displays information about Summer Shows', 'udpac' ), )
        );
    }

    // Creating widget front-end

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        if( have_rows('show_sponsors') ):
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
          <?php // Create and run custom loop
            $custom_posts = new WP_Query();
            $custom_posts->query('post_type=production&posts_per_page=8');
            while ($custom_posts->have_posts()) : $custom_posts->the_post();
          ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile; ?>
          <?php wp_reset_postdata();
          echo $args['after_widget'];
        endif;
    }

    // Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'udpac' );
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class udpac_production_sponsor_widget ends here