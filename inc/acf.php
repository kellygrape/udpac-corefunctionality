<?php
/**
 * ACF
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
Plugin Name: ACF - Advanced Custom Fields
Description: Advanced Custom Fields - registering fields, field option page
Version: 1.0
Author: Kelly Anne Pipe

1) Turn on ACF Options page
2) Register Season Taxonomy
3) Production Show Type Taxonomy
4) Production open/close cron job
*/

/* Register Production Post Type CPT */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}
