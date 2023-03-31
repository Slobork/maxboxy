<?php
/**
 * Plugin Name:         MaxBoxy
 * Description:         Conversion Boxes, Popups, Float and Inject Any Content
 *
 * PHP version  7.3.5
 *
 * @category Conversion
 * @package  MaxBoxy
 * @author   MaxPressy <webmaster@maxpressy.com>
 * @license  GPL v2 or later
 * @link     maxpressy.com
 *
 * Author:              MaxPressy
 * Author URI:          https://maxpressy.com
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Version:             1.0.6
 * Text Domain:         maxboxy
 * Domain Path:         /languages
 * Requires at least:   5.8
 */

if (! defined('ABSPATH') ) {
    exit;
}

    require_once 'admin/admin-init.php';
    require_once 'classes/init.php';
    require_once 'classes/reusable-blocks.php';
    require_once 'classes/options.php';
    require_once 'classes/admin-columns.php';
    require_once 'classes/track.php';
    require_once 'patterns.php';
