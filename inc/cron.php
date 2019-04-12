<?php
/**
 * Production Post Type and Related Functionality
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
Plugin Name: Production Post Type and Related Functionality
Description: Init the Production post type and related functionality
Version: 1.0
Author: Kelly Anne Pipe

1) Register Production CPT
2) Register Season Taxonomy
3) Production Show Type Taxonomy
4) Production open/close cron job
5) Print Sponsor List
6) Print Dates Array
7) Make Dates Array
8) Register Open Close - runs on save of post

*/

/* Register Production Post Type CPT */
function udpac_production_post_type() {
    $labels = array(
       'name'                => _x( 'Productions', 'Post Type General Name', 'text_domain' ),
       'singular_name'       => _x( 'Production', 'Post Type Singular Name', 'text_domain' ),
       'menu_name'           => __( 'Production', 'text_domain' ),
       'parent_item_colon'   => __( 'Parent Production:', 'text_domain' ),
       'all_items'           => __( 'All Productions', 'text_domain' ),
       'view_item'           => __( 'View Production', 'text_domain' ),
       'add_new_item'        => __( 'Add New Production', 'text_domain' ),
       'add_new'             => __( 'Add New', 'text_domain' ),
       'edit_item'           => __( 'Edit Production', 'text_domain' ),
       'update_item'         => __( 'Update Production', 'text_domain' ),
       'search_items'        => __( 'Search Productions', 'text_domain' ),
       'not_found'           => __( 'Not found', 'text_domain' ),
       'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
    );
    $rewrite = array(
       'slug'                => 'show',
       'with_front'          => true,
       'pages'               => false,
       'feeds'               => true,
    );
    $args = array(
       'label'               => __( 'production', 'text_domain' ),
       'description'         => __( 'A Show or Production', 'text_domain' ),
       'labels'              => $labels,
       'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
       'taxonomies'          => array( 'category' ),
       'hierarchical'        => false,
       'public'              => true,
       'show_ui'             => true,
       'show_in_menu'        => true,
       'show_in_nav_menus'   => true,
       'show_in_admin_bar'   => true,
       'menu_position'       => 5,
       'menu_icon'           => '',
       'can_export'          => true,
       'has_archive'         => true,
       'exclude_from_search' => false,
       'publicly_queryable'  => true,
       'rewrite'             => $rewrite,
       'capability_type'     => 'page',
    );
    register_post_type( 'production', $args );
};

add_action('init', 'udpac_production_post_type');

/* Register Season Taxonomy */
function udpac_register_season_taxonomy() {
    register_taxonomy('season',array('production'),array(
      'label' => 'Season',
      'public'=>true,
      'hierarchical'=>true,
      'show_ui'=>true,
      'query_var'=>true,
      'rewrite'=> array(
            'slug'         => 'season',
            'with_front'   => true,
            'hierarchical' => false,
      )
    ));
}

add_action('init', 'udpac_register_season_taxonomy');

/* Production Show Type Taxonomy */
function udpac_register_production_taxonomy() {
  $labels = array(
      'name'                       => _x( 'Production Types', 'Taxonomy General Name', 'text_domain' ),
      'singular_name'              => _x( 'Production Type', 'Taxonomy Singular Name', 'text_domain' ),
      'menu_name'                  => __( 'Production Type', 'text_domain' ),
      'all_items'                  => __( 'All Production Types', 'text_domain' ),
      'parent_item'                => __( 'Parent Production Type', 'text_domain' ),
      'parent_item_colon'          => __( 'Parent Production Type:', 'text_domain' ),
      'new_item_name'              => __( 'New Production Type', 'text_domain' ),
      'add_new_item'               => __( 'Add Production Type', 'text_domain' ),
      'edit_item'                  => __( 'Edit Production Type', 'text_domain' ),
      'update_item'                => __( 'Update Production Type', 'text_domain' ),
      'separate_items_with_commas' => __( 'Separate production types with commas', 'text_domain' ),
      'search_items'               => __( 'Search production types', 'text_domain' ),
      'add_or_remove_items'        => __( 'Add or remove production types', 'text_domain' ),
      'choose_from_most_used'      => __( 'Choose from the most used production types', 'text_domain' ),
      'not_found'                  => __( 'Not Found', 'text_domain' ),
  );
  $args = array(
      'labels'                     => $labels,
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => false,
      'rewrite'=> array(
          'slug'         => 'showtype',
          'with_front'   => true,
          'hierarchical' => false,
    )
  );
  register_taxonomy( 'production_type', array( 'production' ), $args );
};

add_action('init', 'udpac_register_production_taxonomy');


/* Production Cron Job

    Creates a cron job that should run daily to update all the productions with the correct opening date and closing date.
    We want the opening date to be the NEXT MOST RECENT DATE that this show will run.
    This helps us with ordering non-consecutive productions

    */
function update_open_close_cron_fn( ) {
    $posts = get_posts( [
        'post_type' => 'production',
        'posts_per_page' => -1, // getting all posts of a post type
        'no_found_rows' => true, //speeds up a query significantly and can be set to 'true' if we don't use pagination
        'fields' => 'ids', //again, for performance
    ] );

    //now check meta and update taxonomy for every post
    $threeWeeksAgo = new DateTime();
    $threeWeeksAgo->modify('-3 weeks');
    $today = new DateTime();
    foreach( $posts as $post_ID ){
        $currOpening = DateTime::createFromFormat('Ymd', get_field('field_535fe8b985006', $post_ID));
        $currClosing = DateTime::createFromFormat('Ymd', get_field('field_535fe8e885007', $post_ID));

        $datearray = make_dates_array($post_ID);

        if($currClosing > $today && count($datearray) > 1) {
            // if the event has NOT ended\
            if ($currOpening < $threeWeeksAgo) {
                // opened more than three weeks ago
                for($i = 0; $i < count($datearray); $i++){
                    $theDate = DateTime::createFromFormat('Ymd', $datearray[$i]['date']);
                    if($theDate > $today) {
                        update_field('field_535fe8b985006', $datearray[$i]['date'], $post_ID);
                        break;
                    }
                }
            }
        }
    }
}
// execute the function "update_open_close_cron_fn" when the action "UDPAC_update_open_close_cron" is launched
add_action("UDPAC_update_open_close_cron", "update_open_close_cron_fn");

add_action("wp_loaded", function () {
    // launch the action "UDPAC_update_open_close_cron" every 15 minutes
    if (!wp_next_scheduled("UDPAC_update_open_close_cron")) {
        wp_schedule_event(current_time( 'timestamp' ), "daily", "UDPAC_update_open_close_cron");
    }
});

/*
    Print Sponsor List
*/

function print_sponsor_list(){
  echo '<ul>';
  while ( have_rows('show_sponsors') ) : the_row();
    echo '<li>';
    $image = get_sub_field('sponsor_logo');
    if(has_sub_field('sponsor_link')): echo '<a href="'.get_sub_field('sponsor_link').'">';
    endif;

    if( !empty($image) ): echo '<img src="'.$image['url'].'" alt="'.get_sub_field('sponsor_name').'" />';
    else: echo get_sub_field('sponsor_name');
    endif;

    if(has_sub_field('sponsor_link')): echo '</a>';
    endif;
    echo '</li>';
  endwhile;
  echo '</ul>';
}

/**
 * Print Dates Array
 */
function print_dates_array($array){
  $opendate = DateTime::createFromFormat('Ymd', get_field('opening_night'));
  ?><ul class="dateslist"><?php
  for ($i = 0; $i < count($array); $i++) {
    if($i == 0):
      $datestring = $opendate->format('Y-m-d');
      $datestring .= 'T'.date('H:i',strtotime($array[$i]['time']));
    endif;
    ?>
    <li>
        <span class="thedate" <?php if($i == 0): echo 'itemprop="startDate" content="'.$datestring.'"'; endif; ?>>
        <?php echo date('D M j, Y',strtotime($array[$i]['date'])); ?>
        </span>
        <span class="thetime"><?php echo date('g:i a',strtotime($array[$i]['time'])); ?></span>

        <?php if($array[$i]['sold_out'] == '1'): ?>
        <span class="soldout">Tickets sold out for this performance</span>
        <?php endif; ?>
    </li>
<?php } ?>
    </ul>
<?php
}

/**
 * Make Dates Array
 */
function make_dates_array($post_ID){
  $dates = [];
  if(have_rows('show_dates', $post_ID)):
    $showDates = get_field('show_dates', $post_ID);
    for($j = 0; $j < count($showDates); $j++){
        $dates[$j] = array(
          'date' => $showDates[$j]['date'],
          'time' => $showDates[$j]['time'],
          'sold_out' => $showDates[$j]['sold_out']
      );
    }
  endif;
  return $dates;
}

/**
 * Register Open Close - runs on save of post
 */
function register_open_close($post_ID)  {
  if('production'==get_post_type($post_ID)):
    $datearray = make_dates_array($post_ID);
    if(!empty($datearray)):
      update_field('field_535fe8b985006', $datearray[0]['date'], $post_ID);

      end($datearray);
      $key = key($datearray);
      update_field('field_535fe8e885007', $datearray[$key]['date'], $post_ID);
    endif;
  else:
  //do nothing
  endif;
  return $post_ID;
}

add_action('save_post', 'register_open_close');
