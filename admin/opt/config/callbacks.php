<?php
// phpcs:ignore
/**
 * Description: Callbacks for Metabox options
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('maxboxy_upgrade_call')) {

    /**
     * Upgrade notice callback for metabox.
     *
     * If pro version isn't active, in UI it displays the call for upgrade.
     * 
     * @return string Print the upgrade call.
     */
    // phpcs:ignore
    function maxboxy_upgrade_call()
    {

        $get_license = class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== '' ? true : false;

        // if the Pro version is active but the license not actvated
        if (class_exists('Max_Boxy_Pro') && $get_license === false) {

            /*
             * @see _safe_license_notactive_notice()
             * from Max_Boxy class - all esacped there.
             */
            echo '<div style="padding: 0 10px; border-left: 4px solid red;">' .Max_Boxy::_safe_license_notactive_notice() .'</div>';

            // else would be called if the Pro version isn't active at all
        } else {
            echo '<div style="padding: 0 10px; border-left: 4px solid #339fd4;">' .__('Upgrade to ', 'maxboxy') .'<a href="https://maxpressy.com/maxboxy/wordpress-floating-content-box-plugin-injection/?mtm_campaign=pluginAdminUpgrade&mtm_kwd=maxboxy" target="_blank">' .__('Pro version', 'maxboxy') .'</a> to gain access to premium features.</div>';
        }

    }

}


if (! function_exists('maxboxy_stats_call')) {

    /**
     * Tracking stats - callback for the "Conversion" metabox.
     * Used in Max_Boxy_Track::reset_panel_stats() as well.
     *
     * @param int $id Get the id of the panel.
     * 
     * @return string Displays the stats.
     */
    // phpcs:ignore
    function maxboxy_stats_call( $id )
    {

        /*
         * $id passed from @see maxboxy_splitters_stats(),
         * otherwise it's the current post id
         */
        $id = ! empty($id) ? $id : get_the_ID();

        $get_license = class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== '' ? true : false;

        // if the Pro is active push the stats pro
        if ($get_license !== false && function_exists('maxboxy_stats_call_pro')) {
            maxboxy_stats_call_pro($id);
        } else {
            $loaded_volume = Max_Boxy_Track::get_load_count($id)[ 'volume' ];
            $loaded_unique = Max_Boxy_Track::get_load_count($id)[ 'unique' ];

            $views_volume  = Max_Boxy_Track::get_views_count($id)[ 'volume' ];
            $views_unique  = Max_Boxy_Track::get_views_count($id)[ 'unique' ];

            $goals_volume  = Max_Boxy_Track::get_goals_count($id)[ 'volume' ];
            $goals_unique  = Max_Boxy_Track::get_goals_count($id)[ 'unique' ];

            // Only for the 'post' page, i.e. omit for the listing (i.e. post type page)
            if (isset($_GET['post'])) {

                // Load/Views/goals stats
                echo '<div class="maxboxy-stats-fraction" title="'.__('Volume / Unique', 'maxboxy') .'">
                        <div>' .esc_html('Loads',  'maxboxy') .'</div>
                        <div>' .esc_html('Views', 'maxboxy') .'</div>
                        <div>' .esc_html('Goals', 'maxboxy') .'</div>
                        <div>' .esc_html($loaded_volume) .'/' .esc_html($loaded_unique) .'</div>
                        <div>' .esc_html($views_volume)  .'/' .esc_html($views_unique)  .'</div>
                        <div>' .esc_html($goals_volume)  .'/' .esc_html($goals_unique)  .'</div>
                    </div>';

                echo '<div>' .__('Conversion rate: ', 'maxboxy') .'</div>';

            }

            $ratio = is_numeric($goals_volume) && is_numeric($loaded_volume) ? $goals_volume / $loaded_volume *100 : '';
            $ratio = is_numeric($ratio) ? number_format($ratio, 2) .'%' : 0;
            echo'<div><strong>' .esc_html($ratio) .'</strong></div>';

            /*
             * Only for the 'post' page, i.e. omit for the listing
             * (i.e. post type page), but not on the splitter's post type.
             */
            if (isset($_GET['post']) && get_post_type($_GET['post']) !== 'maxboxy_splitter') {
                echo '<br/>';
                // Upgrade call
                echo '<div class="maxboxy-stats-more-pro">' .__('More stats with Maxboxy Pro: ', 'maxboxy') .'</div>';
                maxboxy_upgrade_call();
            }

        }

        /*
         * Only for the 'post' page, i.e. omit for the listing
         * (i.e. post type page), but not on the splitter's post type.
         */
        if (isset($_GET['post']) && get_post_type($_GET['post']) !== 'maxboxy_splitter') {
            echo '<br/>';
            // for both, free & pro, set the 'reset' button
            echo '<button class="maxboxy-reset-stats">' .__('Reset', 'maxboxy') .'</button>';
        }

    }

}
