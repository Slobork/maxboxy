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
    add_filter(
        'manage_float_any_posts_columns', array ( 'Max_Boxy_Admin_Columns', 'add_admin_columns' )
    );

    add_action(
        'manage_float_any_posts_custom_column', array ( 'Max_Boxy_Admin_Columns', 'admin_custom_columns_data' )
    );

    // inject_any post type
    add_filter(
        'manage_inject_any_posts_columns', array ( 'Max_Boxy_Admin_Columns', 'add_admin_columns' )
    );

    add_action(
        'manage_inject_any_posts_custom_column', array ( 'Max_Boxy_Admin_Columns', 'admin_custom_columns_data' )
    );

    // 'wp_block' i.e. reusable blocks post type (if enabled)
    if (class_exists('Max_Boxy_Reusable_Blocks') && Max_Boxy_Reusable_Blocks::enabled() === true) {

        add_filter(
            'manage_wp_block_posts_columns', array ( 'Max_Boxy_Admin_Columns', 'add_admin_columns' )
        );

        add_action(
            'manage_wp_block_posts_custom_column', array ( 'Max_Boxy_Admin_Columns', 'admin_custom_columns_data' )
        );

    }

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

            if (Max_Boxy_Track::enabled() !== true) {
                return $columns;
            }

            $columns['post_loaded'] = esc_html__('Loads (V/U)',  'maxboxy');
            $columns['post_views']  = esc_html__('Views (V/U)',  'maxboxy');
            $columns['post_goals']  = esc_html__('Goals (V/U)',  'maxboxy');
            $columns['conversion']  = esc_html__('Conversion',   'maxboxy');
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

            if (Max_Boxy_Track::enabled() !== true) {
                return;
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

        }


    } // end class

} // end class exists check