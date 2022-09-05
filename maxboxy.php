<?php

	if ( ! defined( 'ABSPATH' ) ) exit;

	/**
	 * Plugin Name:         MaxBoxy Conversions - Float Any and Inject Any Content
	 * Description:         Make Popups, Notifications, Conversion Boxes, Distinguished Call to Actions, Ad Boxes - Any Content Floating, Sticking and Injecting with Stats and various output ways.
	 * Author:              MaxPressy
	 * Author URI:          https://maxpressy.com
	 * License:             GPL v3
	 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
	 * Version:             1.0.1
	 * Text Domain:         maxboxy
	 * Domain Path:         /languages
	 * Requires at least:   5.8
	 * Requires PHP:        7.3.5
	 */

	include_once 'admin/admin-init.php';
	include_once 'classes/init.php';
	include_once 'classes/reusable-blocks.php';
	include_once 'classes/options.php';
	include_once 'classes/admin-columns.php';
	include_once 'classes/track.php';
	include_once 'patterns.php';
