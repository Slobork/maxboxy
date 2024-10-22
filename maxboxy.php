<?php
/**
 * Plugin Name:         MaxBoxy
 * Description:         Make Conversion Boxes, Popups, Floats and Inject Any Content in a WorsPress way!
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
 * Version:             1.1.7
 * Text Domain:         maxboxy
 * Domain Path:         /languages
 * Requires at least:   5.8
 */

if (! defined('ABSPATH') ) {
    exit;
}

// Plugin data (getting plugin version, name, etc.)
if (! function_exists('get_plugin_data')) {
    include_once ABSPATH .'wp-admin/includes/plugin.php';
}
$plugin_data = get_plugin_data(__FILE__);
define('MAXBOXY', ($plugin_data && $plugin_data['Name']) ? $plugin_data['Name'] : 'MaxBoxy');
define('MAXBOXY_VERSION', ($plugin_data && $plugin_data['Version']) ? $plugin_data['Version'] : '1.0.0');

require_once 'admin/admin-init.php';
require_once 'classes/init.php';
require_once 'classes/reusable-blocks.php';
require_once 'classes/options.php';
require_once 'classes/admin-columns.php';
require_once 'classes/track.php';
require_once 'patterns.php';
