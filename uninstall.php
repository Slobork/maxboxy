<?php

/**
 * Description: Uninstalling option. Optionally, on user choise, 
 * get the plugin's options removed from a database on uninstall.
 * 
 * PHP version 7.3.5
 * 
 * @category Conversion
 * @package  MaxBoxy
 * @author   MaxPressy <webmaster@maxpressy.com>
 * @license  GPL v2 or later
 * @link     maxpressy.com
 */
if (! defined('ABSPATH')) { 
    exit; 
}

// if uninstall.php is not called by WordPress, die
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

$uninstall_setting = isset(get_option('_maxboxy_options')['uninstall_setting'])
                   ?       get_option('_maxboxy_options')['uninstall_setting'] : '';

// unisitall if user prompts it
if (! empty($uninstall_setting)) {
    $option_name = '_maxboxy_options';
    delete_option($option_name);
}
