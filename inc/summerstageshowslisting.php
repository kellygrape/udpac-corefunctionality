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
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] );
        // before and after widget arguments are defined by themes
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $shows = new WP_Query(array(
            'post_type' => 'production',
            'posts_per_page' => -1,
            'meta_key' => 'closing_night',
            'meta_compare' => '>=',
            'meta_value' => date('Ymd'),
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
        ));

        if($shows->have_posts()):
            ?><div class="show-list"><?php
            while($shows->have_posts()) : $shows->the_post();
            ?> 
            <style>
            .show-list {
                display: flex;
                flex-wrap: wrap;
            }
            .show-list-item {
                flex-basis: 30%;
                margin: 10px;
                flex: 1 1 30%;
            }
            </style>
            <div class="show-list-item">
                <a href="<?php the_permalink(); ?>">
                <figure class="show-thumbnail">
                    <?php the_post_thumbnail(); ?>
                </figure>
                <div class="show-info">
                    <h3 class="show-name">
                        <?php the_title(); ?>
                    </h3>
                    <p class="show-dates"></p>
                </div>
                </a>
            </div><?php
            endwhile;
            ?></div><?php
        endif;
        wp_reset_query();
        echo $args['after_widget'];
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