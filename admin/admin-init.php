<?php
// phpcs:ignore
/**
 * Description: Initializing admin
 */

if (! defined('ABSPATH')) {
    exit;
}

/*
 * Include CSF, fields framework.
 */
if (! class_exists('CSF')) {
    include_once dirname(__FILE__) .'/codestar-framework/codestar-framework.php';
}

if (class_exists('CSF')) {

    // Disable CSF welcome page
    add_filter('csf_welcome_page', '__return_false');

    if (class_exists('Max_Boxy_Pro')) {
        include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/callbacks.php';
        include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/framework.php';
        include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/metabox.php';
        include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/shortcode.php';
    }

    include_once dirname(__FILE__) .'/opt/config/callbacks.php';
    include_once dirname(__FILE__) .'/opt/config/framework.php';
    include_once dirname(__FILE__) .'/opt/config/metabox.php';

}


if (! function_exists('maxboxy_admin_scripts')) {

    /**
     * Enqueue admin scripts.
     * 
     * @return void Hook to the enqueueing functions.
     */
    // phpcs:ignore
    function maxboxy_admin_scripts()
    {
        wp_enqueue_script('maxboxy-adminizr', plugin_dir_url(__DIR__) .'library/admin/min/adminizr.js', array('jquery'), MAXBOXY_VERSION, true);

        // for debugging
        //wp_enqueue_script( 'maxboxy-adminizr', plugin_dir_url(  __DIR__  ) .'library/admin/adminizr.js', array( 'jquery' ), MAXBOXY_VERSION, true );

        $local_var_array  = array(
            'post_id'  => get_the_ID(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'mb_nonce' => wp_create_nonce('mb-nonce'),
        );

        wp_localize_script('maxboxy-adminizr', 'maxboxy_localize', $local_var_array);

    }

    add_action(
        'admin_enqueue_scripts', 'maxboxy_admin_scripts'
    );

}


/**
 * Redirect to settings page on plugin activation.
 */
if (! function_exists('maxboxy_redirect_on_activation')) {

    /**
     * Redirect to plugin settings page on activation.
     *
     * @return void
     */
    function maxboxy_redirect_on_activation()
    {
        set_transient('maxboxy_redirect_to_settings', true, 30);
    }

    /**
     * Perform redirect if transient is set.
     *
     * @return void
     */
    function maxboxy_do_redirect_on_activation()
    {
        if (get_transient('maxboxy_redirect_to_settings')) {
            delete_transient('maxboxy_redirect_to_settings');
            wp_safe_remote_post(admin_url('admin.php?page=maxboxy-settings'));
            wp_redirect(admin_url('admin.php?page=maxboxy-settings'));
            exit;
        }
    }

    register_activation_hook(dirname(dirname(__FILE__)) . '/maxboxy.php', 'maxboxy_redirect_on_activation');
    add_action('admin_init', 'maxboxy_do_redirect_on_activation');

}
