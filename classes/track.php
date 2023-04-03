<?php
// phpcs:ignore
/**
 * Description: Conversion Tracking for MaxBoxy
 */

if (! defined('ABSPATH')) { 
    exit; 
}


if (! class_exists('Max_Boxy_Track')) {

        add_action(
            'body_class', array( 'Max_Boxy_Track', 'body_classes' )
        );

        add_action(
            'wp_ajax_maxboxy_update_load', array('Max_Boxy_Track', 'update_load_count')
        );

        add_action(
            'wp_ajax_nopriv_maxboxy_update_load', array('Max_Boxy_Track', 'update_load_count')
        );

        add_action(
            'wp_ajax_maxboxy_update_views', array( 'Max_Boxy_Track', 'update_views_count')
        );

        add_action(
            'wp_ajax_nopriv_maxboxy_update_views', array( 'Max_Boxy_Track', 'update_views_count')
        );

        add_action(
            'wp_ajax_maxboxy_update_goals', array( 'Max_Boxy_Track', 'update_goals_count')
        );

        add_action(
            'wp_ajax_nopriv_maxboxy_update_goals', array( 'Max_Boxy_Track', 'update_goals_count')
        );


        add_action(
            'wp_ajax_maxboxy_reset_panel_stats', array( 'Max_Boxy_Track', 'reset_panel_stats')
        );


    // phpcs:ignore
    class Max_Boxy_Track
    {

        /**
         * Check is the Conversions module enabled from the general options.
         *
         * @return boolean
         */
        public static function enabled()
        {

            $enabled    =    isset(get_option('_maxboxy_options')[ 'enable_conversions' ])
                        ?          get_option('_maxboxy_options')[ 'enable_conversions' ] : '';

            $enabled    =    ! empty($enabled) ? true : false;

                return $enabled;

        }


        /**
         * Add class to "body_classes" pointing that tracking is on.
         * 
         * @param string $classes Add a tracking class, value 'maxboxy-tracking-on'.
         * 
         * @return string 
         */
        // phpcs:ignore
        public static function body_classes( $classes )
        {

            if (self::enabled() === true) {
                $classes[] = 'maxboxy-tracking-on';
            }
            return $classes;
        }


        /**
         * Update the load - retreive from JS ajax.
         * 
         * @return void It's updating the meta value.
         */
        // phpcs:ignore
        public static function update_load_count()
        {

            if (self::enabled() !== true) {
                return;
            }

            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mb-nonce')) {
                wp_die(-1);
            }

            // get data from the client
            $panel_id     = isset($_POST['panel_id']) ? (int) $_POST['panel_id'] : '';
            $new_visitor  = ! empty($_POST['new_visitor']) ? true : false;

            // get tracking data
            $get_id       = $panel_id;
            $track        = Max_Boxy_Options::conversions($get_id);

                // ...by default do not track logged in users
            if ($track[ 'track_loggedin' ] === false && is_user_logged_in()) {
                return;
            }

            if (is_numeric($panel_id)  && $panel_id > 0) {

                $key        = 'maxboxy_loaded_count';
                $count      = (int) get_post_meta($panel_id, $key, true);
                $prev_value = $count;
                $count++;
                update_post_meta($panel_id, $key, $count, $prev_value);

                if ($new_visitor === true) {
                    $key        = 'maxboxy_loaded_count_unique';
                    $count      = (int) get_post_meta($panel_id, $key, true);
                    $prev_value = $count;
                    $count++;
                    // if not yet created, it adds the post meta
                    update_post_meta($panel_id, $key, $count, $prev_value);
                }

            }

            wp_die();

        }


        /**
         * Update the views count - retreive from JS ajax.
         * 
         * @return void It's updating the meta value.
         */
        // phpcs:ignore
        public static function update_views_count()
        {

            if (self::enabled() !== true) {
                return;
            }

            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mb-nonce')) {
                wp_die(-1);
            }

            // get data from the client
            $panel_id     = isset($_POST['panel_id']) ? (int) $_POST['panel_id'] : '';
            $new_visitor  = ! empty($_POST['new_visitor']) ? true : false;

             // get tracking data
             $get_id      = $panel_id;
             $track       = Max_Boxy_Options::conversions($get_id);

            // ...by default do not track logged in users
            if ($track[ 'track_loggedin' ] === false && is_user_logged_in()) {
                return;
            }

            if (is_numeric($panel_id) && $panel_id > 0) {

                  $key        = 'maxboxy_views_count';
                  $count      = (int) get_post_meta($panel_id, $key, true);
                  $prev_value = $count;
                  $count++;
                  // if not yet created, it adds the post meta
                  update_post_meta($panel_id, $key, $count, $prev_value);

                if ($new_visitor === true) {
                      $key        = 'maxboxy_views_count_unique';
                      $count      = (int) get_post_meta($panel_id, $key, true);
                      $prev_value = $count;
                      $count++;
                      // if not yet created, it adds the post meta
                      update_post_meta($panel_id, $key, $count, $prev_value);
                }
  
            }

                wp_die();

        }


        /**
         * Update the goals count - retreive from JS ajax.
         * 
         * @return void It's updating the meta value.
         */
        // phpcs:ignore
        public static function update_goals_count()
        {

            if (self::enabled() !== true) {
                return;
            }

            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mb-nonce')) {
                wp_die(-1);
            }

            // get data from the client
            $panel_id    = isset($_POST['panel_id']) ? (int) $_POST['panel_id'] : '';
            $new_visitor = ! empty($_POST['new_visitor']) ? true : false;

            // get tracking data
            $get_id      = $panel_id;
            $track       = Max_Boxy_Options::conversions($get_id);

            // ...by default do not track logged in users
            if ($track[ 'track_loggedin' ] === false && is_user_logged_in()) {
                return;
            }

            if (is_numeric($panel_id) && $panel_id > 0) {

                $key        = 'maxboxy_completed_goal';
                $count      = (int) get_post_meta($panel_id, $key, true);
                $prev_value = $count;
                $count++;
                // if not yet created, it adds the post meta
                update_post_meta($panel_id, $key, $count, $prev_value);

                if ($new_visitor === true) {
                     $key        = 'maxboxy_completed_goal_unique';
                     $count      = (int) get_post_meta($panel_id, $key, true);
                     $prev_value = $count;
                     $count++;
                     // if not yet created, it adds the post meta
                     update_post_meta($panel_id, $key, $count, $prev_value);
                }

            }

            wp_die();

        }


        /**
         * Get the data from DB of the load count.
         *
         * @param int|string $id Get the id of the panel.
         * 
         * @return array.
         */
        // phpcs:ignore
        public static function get_load_count( $id )
        {

            if (self::enabled() !== true) {
                return;
            }

             $volume = get_post_meta($id, 'maxboxy_loaded_count', true);
             $unique = get_post_meta($id, 'maxboxy_loaded_count_unique', true);

            $volume  = is_numeric($volume) ? $volume : '-';
            $unique  = is_numeric($unique) ? $unique : '-';

            return array( 'volume' => $volume, 'unique' => $unique );

        }


        /**
         * Get the data from DB of the views count.
         *
         * @param int|string $id Get the id of the panel.
         * 
         * @return array.
         */
        // phpcs:ignore
        public static function get_views_count( $id )
        {

            if (self::enabled() !== true) {
                return;
            }

            $volume = get_post_meta($id, 'maxboxy_views_count', true);
            $unique = get_post_meta($id, 'maxboxy_views_count_unique', true);

            $volume = is_numeric($volume) ? $volume : '-';
            $unique = is_numeric($unique) ? $unique : '-';

            return array( 'volume' => $volume, 'unique' => $unique );

        }


        /**
         * Get the data from DB of the goals count.
         *
         * @param int|string $id Get the id of the panel.
         * 
         * @return array.
         */
        // phpcs:ignore
        public static function get_goals_count( $id )
        {

            if (self::enabled() !== true) {
                return;
            }

            $volume = get_post_meta($id, 'maxboxy_completed_goal', true);
            $unique = get_post_meta($id, 'maxboxy_completed_goal_unique', true);

            $volume = is_numeric($volume) ? $volume : '-';
            $unique = is_numeric($unique) ? $unique : '-';

            return array( 'volume' => $volume, 'unique' => $unique );

        }


        /**
         * Reset panel stats - Ajax call.
         * 
         * @return void It's deleteng the meta value.
         */
        // phpcs:ignore
        public static function reset_panel_stats()
        {

            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mb-nonce')) {
                wp_die(-1);
            }

            $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : '';

            if (is_numeric($post_id) && $post_id > 0) {

                 // reset the stats counts
                 delete_post_meta($post_id, 'maxboxy_loaded_count');
                 delete_post_meta($post_id, 'maxboxy_loaded_count_unique');
                 delete_post_meta($post_id, 'maxboxy_views_count');
                 delete_post_meta($post_id, 'maxboxy_views_count_unique');
                 delete_post_meta($post_id, 'maxboxy_completed_goal');
                 delete_post_meta($post_id, 'maxboxy_completed_goal_unique');

                $id = $post_id;
                // return to the client:
                maxboxy_stats_call($id); // new (reseted) values

            }

            wp_die();

        }


    } // end class

} // end class exists check
