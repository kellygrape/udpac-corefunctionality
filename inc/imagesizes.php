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
Plugin Name: Image Sizes for UDPAC
Description: UDPAC Widgets
Version: 1.0
Author: Kelly Anne Pipe

*/

function udpac_core_image_sizes() {

  add_image_size('featureimage', 1200, 800, true); // 300px wide (and unlimited height)
  //add_image_size('homepage-upcomingshows', 300, 200, true);
  //add_image_size('homepage-cta', 350, 200, true);
  add_image_size('bannerimage',1170, 300, true);
  //add_image_size('bannerimage-inner', 1170, 150, true);
  add_image_size('archive', 800, 400, true); // archive
  add_image_size('archivem', 400, 200, true); // mobile-ready archive
}
add_action('after_setup_theme', 'udpac_core_image_sizes');