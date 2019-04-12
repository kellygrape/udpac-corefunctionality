<?php
/**
 * Beaver Builder
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
Plugin Name: Beaver Builder functionality
Description: Basic Beaver Builder functionality
Version: 1.0
Author: Kelly Anne Pipe

1) UDPAC Show Templates
*/

function udpac_load_bb_templates() {
    if ( ! class_exists( 'FLBuilder' ) || ! method_exists( 'FLBuilder', 'register_templates' ) ) {
        return;
    }
    FLBuilder::register_templates( UDPAC_DIR . '/inc/beaverbuilder/udpacshowtemplates.dat' );
}

add_action( 'init', 'udpac_load_bb_templates' );
