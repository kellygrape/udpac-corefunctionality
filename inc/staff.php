<?php
/**
 * Staff
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
Plugin Name: Staff Custom Post Type and related functionality
Description: Staff CPT and functionality
Version: 1.0
Author: Kelly Anne Pipe

1) Post Type: Staff Members.
2) Taxonomy: Staff Areas.
3) Register columns for our taxonomy
4) Change API Response limit for staff
5) Enqueue scripts and styles
*/

/**
 * Post Type: Staff Members.
 */
function updac_register_my_cpts_staff() {
	$labels = array(
		"name" => __( "Staff Members", "udpac" ),
		"singular_name" => __( "Staff Member", "udpac" ),
	);

	$args = array(
		"label" => __( "Staff Members", "udpac" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "udpacstaff",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "staff", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "editor", "thumbnail" ),
	);

	register_post_type( "staff", $args );
}

add_action( 'init', 'updac_register_my_cpts_staff' );

/**
 * Taxonomy: Staff Areas.
 */

function udpac_register_my_taxes_staffareas() {
	$labels = array(
		"name" => __( "Staff Areas", "udpac" ),
		"singular_name" => __( "Staff Area", "udpac" ),
	);

	$args = array(
		"label" => __( "Staff Areas", "udpac" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'staffareas', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "staffareas",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		);
	register_taxonomy( "staffareas", array( "staff" ), $args );
}
add_action( 'init', 'udpac_register_my_taxes_staffareas' );

/**
 * Register columns for our taxonomy
 */
//first register the column
add_filter('manage_staff_posts_columns', 'staff_columns');
function staff_columns($defaults){
    $defaults['staff_area'] = __('Staff Area');
    return $defaults;
}

//then you need to render the column
add_action('manage_staff_posts_custom_column', 'staff_custom_columns', 5, 2);
function staff_custom_columns($column_name, $post_id){
    if($column_name === 'staff_area'){
      $staffareas = get_the_terms($post_id,'staffareas');
      if ($staffareas){
        foreach ($staffareas as $staffarea) echo $staffarea->name;
      }
    }
}

/**
 * Change API Response limit for staff
 */
// ADD ABILITY FOR STAFF TO HAVE THE API WITH LOTS OF ITEMS IN THE RESPONSE
add_filter( 'rest_udpacstaff_collection_params', 'udpacstaff_change_post_per_page', 10, 2 );

function udpacstaff_change_post_per_page( $params, $request ) {
    $max = max( (int) $request->get_param( 'per_page' ), 200 );
    $params['per_page']['maximum'] = $max;
    return $params;
}

/**
 * Enqueue scripts and styles
 */
add_action('wp_enqueue_scripts', 'enqueue_stafflist_scripts');
function enqueue_stafflist_scripts() {
  if(is_page_template('template-staffpage.php') || is_singular('production')){
    wp_register_script( 'stafflist', plugin_dir_url( __FILE__ ) . 'js/stafflist.js', array(), 
        filemtime(plugin_dir_url( __FILE__ ) . 'js/stafflist.js'));
    wp_enqueue_script( 'stafflist' );
  }
}
