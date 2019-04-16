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
Plugin Name: Widgets for UDPAC
Description: UDPAC Widgets
Version: 1.0
Author: Kelly Anne Pipe

1) Production Sponsor Widget
2) Production Tickets Widget (Tickets Area)
3) Register sidebars and widgets
*/

// Register and load the widget
function udpac_production_sponsor_load_widget() {
    register_widget( 'udpac_production_sponsor_widget' );
    register_widget( 'udpac_production_tickets_widget' );
}
add_action( 'widgets_init', 'udpac_production_sponsor_load_widget' );

// Creating the widget
class udpac_production_sponsor_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            // Base ID of your widget
            'udpac_production_sponsor_widget_id',
            // Widget name will appear in UI
            __('Production Sponsor', 'udpac'),
            // Widget description
            array( 'description' => __( 'Displays information about production sponsors', 'udpac' ), )
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
          echo '<ul class="list-group">';
          while ( have_rows('show_sponsors') ) : the_row();
            $image = get_sub_field('sponsor_logo');
            $name = get_sub_field('sponsor_name');
            $link = get_sub_field('sponsor_link');
            if(!empty($link)):
              echo '<a href="'.$link.'">';
            endif;
            if( !empty($image) ):
              echo '<img src="'.$image['url'].'" alt="'.$name.'" />';
            else:
              echo $name;
            endif;
            if($link != ''):
              echo '</a>';
            endif;
          endwhile;
          echo '</ul>';
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

class udpac_production_tickets_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            // Base ID of your widget
            'udpac_production_tickets_widget_id',
            // Widget name will appear in UI
            __('Ticket Information', 'udpac'),
            // Widget description
            array( 'description' => __( 'Displays ticket information on a production page', 'udpac' ), )
        );
    }

    // Creating widget front-end

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_title'] . $title . $args['after_title'];
        print_dates_array(make_dates_array($GLOBALS['post']->ID));

        if(get_field('ticket_information')):
        ?>
        <div class="ticketprices">
          <h4>Ticket Prices</h4>
          <?php the_field('ticket_information'); ?>
        </div>
        <?php
        endif;

        if( get_field('hide_ticket_button') ):
          // do nothing
        else:
          if(get_field('door_tickets')):
            echo '<button class="btn btn-default btn-block"><i class="fa fa-ticket"></i> Tickets available <br>at the door</button>';
          else:
          ?><a href="<?php the_field('ticket_link'); ?>" class="btn btn-tickets btn-block"><i class="fa fa-ticket"></i> Buy Tickets Now!</a>
          <?php
          endif;
        endif;
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
} // Class udpac_production_tickets_widget ends here



/**
 * Register sidebars and widgets
 */
function udpac_widgets_init() {
  // Sidebars
  $sideargs = array(

    );
  register_sidebar(array(
    'name'          => __('News Section Sidebar', 'udpac'),
    'id'            => 'news-sidebar',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => __('About Section Sidebar', 'udpac'),
    'id'            => 'about-sidebar',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => __('Summer Stage Section Sidebar', 'udpac'),
    'id'            => 'summerstage-sidebar',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => __('Custom Sidebar', 'udpac'),
    'id'            => 'sidebar-custom',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => __('Shows Sidebarsss', 'udpac'),
    'id'            => 'sidebar-shows',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>'
  ));

  register_sidebar(array(
    'name'          => __('Footer Left', 'udpac'),
    'id'            => 'sidebar-footer-left',
    'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3 class="widget-header"><span>',
    'after_title'   => '</span></h3>',
  ));

  register_sidebar(array(
    'name'          => __('Footer Center', 'udpac'),
    'id'            => 'sidebar-footer-center',
    'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3 class="widget-header"><span>',
    'after_title'   => '</span></h3>',
  ));
  register_sidebar(array(
    'name'          => __('Footer Right', 'udpac'),
    'id'            => 'sidebar-footer-right',
    'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3 class="widget-header"><span>',
    'after_title'   => '</span></h3>',
  ));
  // Widgets
}
add_action('widgets_init', 'udpac_widgets_init');
