<?php
// phpcs:ignore
/**
 * Description: Uninstalling option. Optionally, on user choise, 
 * get the plugin's options removed from a database on uninstall.
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
