<?php
/**
 * Plugin Name: UDPAC Core Functionality
 * Description: This contains all your site's core functionality so that it is theme independent. <strong>It should always be activated</strong>.
 * Version:     1.2.0
 * Author:      Kelly Anne Pipe from Bill Erickson & Jared Atchison
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.  You may NOT assume that you can use any other
 * version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    UDPAC_CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2019, Kelly Anne Pipe from Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */

// Plugin directory
define( 'UDPAC_DIR' , plugin_dir_path( __FILE__ ) );

//require_once( UDPAC_DIR . '/inc/general.php' );
//require_once( UDPAC_DIR . '/inc/wordpress-cleanup.php' );
require_once( UDPAC_DIR . '/inc/acf.php' );
require_once( UDPAC_DIR . '/inc/cron.php' );
require_once( UDPAC_DIR . '/inc/staff.php' );
require_once( UDPAC_DIR . '/inc/beaverbuilder.php' );
require_once( UDPAC_DIR . '/inc/widgets.php' );
