<?php
// phpcs:ignore
/**
 * Description: Hook to Admin columns
 */

if (! defined('ABSPATH')) { 
    exit; 
}

if (! class_exists('Max_Boxy_Admin_Columns')) {

    // float_any post type
    add_filter('manage_float_any_posts_columns', array ('Max_Boxy_Admin_Columns', 'add_admin_columns'));
    add_action('manage_float_any_posts_custom_column', array ('Max_Boxy_Admin_Columns', 'admin_custom_columns_data'));

    // inject_any post type
    add_filter('manage_inject_any_posts_columns', array ('Max_Boxy_Admin_Columns', 'add_admin_columns'));
    add_action('manage_inject_any_posts_custom_column', array ('Max_Boxy_Admin_Columns', 'admin_custom_columns_data'));


    // phpcs:ignore
    class Max_Boxy_Admin_Columns
    {

        /**
         * Add columns.
         * 
         * @param string $columns Targeted columns.
         * 
         * @return string Columns' heading.
         */
        // phpcs:ignore
        public static function add_admin_columns( $columns )
        {

            //if (Max_Boxy_Track::enabled() !== true) {
            //    $columns['shortcode']    = esc_html__('Shortcode', 'maxboxy');
            //    return $columns;
            //}
               
            // License is Not set or Conversion tracking is Not enabled, show states_empty column
            if(!class_exists('Max_Boxy_Pro') || class_exists('Max_Boxy_Pro') && (Max_Boxy_Pro::getLicense() === '' || Max_Boxy_Track::enabled() !== true)) {
                $columns['states_empty'] = esc_html__('States',    'maxboxy');
            }
            
            // License is set and Conversion tracking is enabled, show each stats column
            if (class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== '' && Max_Boxy_Track::enabled() === true) {
                $columns['post_loaded'] = esc_html__('Loads (V/U)',  'maxboxy');
                $columns['post_views']  = esc_html__('Views (V/U)',  'maxboxy');
                $columns['post_goals']  = esc_html__('Goals (V/U)',  'maxboxy');
                $columns['conversion']  = esc_html__('Conversion',   'maxboxy');
            }

            $columns['shortcode']   = esc_html__('Shortcode',    'maxboxy');
            return $columns;

        }


        /**
         * Add data to the columns.
         *
         * @param string $column Targeted column's data.
         *
         * @return string Columns' data.
         */
        // phpcs:ignore
        public static function admin_custom_columns_data( $column )
        {

            // echo states message ("Empty states" if license is not set or conversion tracking is not enabled)
            if ($column === 'states_empty') {
                
                // if license is Active
                if (class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== '') {

                    // ...but Conversion is not enabled, show enable tracking message
                    if (Max_Boxy_Track::enabled() !== true) {
                        echo esc_html__('Enable conversion from ', 'maxboxy') . '<a href="' . esc_url("admin.php?page=maxboxy-settings#tab=modules") . '" target="_self">' . esc_html__('Settings', 'maxboxy') . '</a>';
                    }
                
                // ...license is not Active
                } else {

                    // ...show activate license message if Pro plugin is activated
                    if (class_exists('Max_Boxy_Pro')) {
                        echo '<a href="' . esc_url("admin.php?page=maxboxy-licenses") . '" target="_self">' . esc_html__('Activate your license first', 'maxboxy') . '</a>';

                    // ...show requires Pro version message
                    } else {
                        echo '<a href="' . esc_url("https://maxpressy.com/maxboxy/wordpress-floating-content-box-plugin-injection/") . '" target="_blank">' . esc_html__('Requires MaxBoxy Pro version', 'maxboxy') . '</a>';
                    }
                }

            }


            $id = get_the_ID();

            // echo loaded count
            if ($column === 'post_loaded') {

                $loaded_volume = Max_Boxy_Track::get_load_count($id)[ 'volume' ];
                $loaded_unique = Max_Boxy_Track::get_load_count($id)[ 'unique' ];

                echo esc_html($loaded_volume) .'/' .esc_html($loaded_unique);

            }

            // echo views count
            if ($column === 'post_views') {

                $views_volume = Max_Boxy_Track::get_views_count($id)[ 'volume' ];
                $views_unique = Max_Boxy_Track::get_views_count($id)[ 'unique' ];

                echo esc_html($views_volume) .'/' .esc_html($views_unique);

            }

            // echo goals complete count
            if ($column === 'post_goals') {

                $goals_volume = Max_Boxy_Track::get_goals_count($id)[ 'volume' ];
                $goals_unique = Max_Boxy_Track::get_goals_count($id)[ 'unique' ];

                echo esc_html($goals_volume) .'/' .esc_html($goals_unique);

            }

            // echo conversion stats
            if ($column === 'conversion') {

                maxboxy_stats_call($id);

            }

            // echo shortcode
            if ($column === 'shortcode') {

                // remove the underscore since the post type is registered with an underscore and shortcodes without
                $is_post_type = get_post_type(get_the_ID()) === 'float_any'  ? 'floatany'  : '';
                $is_post_type = get_post_type(get_the_ID()) === 'inject_any' ? 'injectany' : $is_post_type;
                
                // If Pro plugin activated and post type is float_any or inject_any, show shortcode
                if (class_exists('Max_Boxy_Pro') && !empty($is_post_type)) {

                    // License is set, show shortcode
                    if (Max_Boxy_Pro::getLicense() !== '') {
                        echo esc_html('[' . $is_post_type .' id="' . get_the_ID() . '"]');

                    // ...else show activate license message
                    } else {
                        echo '<a href="' . esc_url("admin.php?page=maxboxy-licenses") . '" target="_self">' . esc_html__('Activate your license first', 'maxboxy') . '</a>';
                    }

                 // ...Pro plugin not activated, show requires Pro version message
                } else {
                    echo '<a href="' . esc_url("https://maxpressy.com/maxboxy/wordpress-floating-content-box-plugin-injection/") . '" target="_blank">' . esc_html__('Requires MaxBoxy Pro version', 'maxboxy') . '</a>';
                }
            }

        }


    } // end class

} // end class exists check
