<?php

	if ( ! defined( 'ABSPATH' ) ) exit;

	/**
	 * Include CSF, fields framework.
	 */
	if ( ! class_exists( 'CSF' ) ) {
		include_once dirname( __FILE__ ) .'/codestar-framework/codestar-framework.php';
	}

	if ( class_exists( 'CSF' ) ) {

		// Disable CSF welcome page
		add_filter( 'csf_welcome_page', '__return_false' );

		if ( class_exists( 'Max__Boxy__Pro' ) ) {
			include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/callbacks.php';
			include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/framework.php';
			include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/metabox.php';
			include_once WP_PLUGIN_DIR .'/maxboxy-pro/admin/opt/config/shortcode.php';
		}

		include_once dirname( __FILE__ ) .'/opt/config/callbacks.php';
		include_once dirname( __FILE__ ) .'/opt/config/framework.php';
		include_once dirname( __FILE__ ) .'/opt/config/metabox.php';

	}


	/**
	 * Enqueue admin scripts.
	 */
	if ( ! function_exists( 'maxboxy_admin_scripts' ) ) {

		function maxboxy_admin_scripts() {

			$plugin_version = Max__Boxy::version();

			wp_enqueue_script( 'maxboxy-adminizr', plugin_dir_url(  __DIR__  ) .'library/admin/min/adminizr.js', array( 'jquery' ), $plugin_version, true );

			// for debugging
			//wp_enqueue_script( 'maxboxy-adminizr', plugin_dir_url(  __DIR__  ) .'library/admin/adminizr.js', array( 'jquery' ), $plugin_version, true );

			$local_var_array  = array(
				'post_id'  => get_the_ID(),
				'ajax_url' => admin_url('admin-ajax.php'),
				'mb_nonce' => wp_create_nonce('mb-nonce'),
				'collapser_attr_title' => esc_html__( 'Expand all', 'maxboxy' ),
			);

			wp_localize_script( 'maxboxy-adminizr', 'maxboxy_localize', $local_var_array );

		}
		add_action( 'admin_enqueue_scripts', 'maxboxy_admin_scripts' );

	}
