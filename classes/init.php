<?php
// phpcs:ignore
/**
 * Description: Plugin's initialization
 */

if (! defined('ABSPATH')) { 
    exit; 
}

if (! class_exists('Max_Boxy')) {

        add_action('init', array( 'Max_Boxy', 'textdomain' ));
        
        add_action('init', array( 'Max_Boxy', 'remove_wpautop' ));

        add_action('wp_enqueue_scripts', array( 'Max_Boxy', 'scripts_and_styles' ));

        add_action('admin_notices', array( 'Max_Boxy', 'admin_notices' ));

        add_action('admin_menu', array( 'Max_Boxy', 'admin_menu' ));

        add_action('init', array( 'Max_Boxy', 'set_post_type_inject_any' ));

        add_action('init', array( 'Max_Boxy', 'set_post_type_float_any' ));

        add_action('init', array( 'Max_Boxy', 'set_taxonomies' ));

        add_action('wp_head', array( 'Max_Boxy', 'loop_injectany_head' ));

        add_action('wp_body_open', array( 'Max_Boxy', 'loop_injectany_top' ));

        add_action('wp_footer', array( 'Max_Boxy', 'loop_injectany_bottom' ));

        add_action('wp_footer', array( 'Max_Boxy', 'loop_floatany' ));


    // phpcs:ignore
    class Max_Boxy 
    {

        /**
         * Load plugin's textdomain.
         * 
         * @return void Hooking a load_plugin_textdomain().
         */
        public static function textdomain()
        {
            load_plugin_textdomain('maxboxy', false, wp_basename(dirname(__FILE__)) . '/languages');
        }


        /**
         * Enqueue place.
         *
         * Get the place where the scripts and styles are loaded.
         *  - Regular enqueue or on demand with the panel.
         *
         * @return string Default 'overall'. Accepts 'on_demand' 
         */
        // phpcs:ignore
        public static function enqueue_place()
        {

            $enqueue_place  = isset(get_option('_maxboxy_options')[ 'enqueue_place' ])
                            ?       get_option('_maxboxy_options')[ 'enqueue_place' ] : '';

            return esc_html($enqueue_place);

        }


        /**
         * Prevent wpautop.
         * 
         * @return void.
         */
        // phpcs:ignore
        public static function remove_wpautop()
        {

            $remove_wpautop  = isset(get_option('_maxboxy_options')[ 'remove_wpautop' ])
                             ?       get_option('_maxboxy_options')[ 'remove_wpautop' ] : '';

            if ($remove_wpautop !== '1' ) {
                // Stop WP adding extra <p> </p> to your pages' content
                remove_filter('the_content', 'wpautop');
            }

        }


        /**
         * Enqueue if it's place on demand.
         * 
         * @return void Calling enqueueing functions.
         */
        // phpcs:ignore
        public static function enqueue_place_on_demand()
        {

            if (self::enqueue_place() === 'on_demand') {

                wp_enqueue_style('maxboxy');
                wp_enqueue_style('maxboxy-pro'); // if pro version is active

                // make sure jquery is loading
                wp_enqueue_script('jquery');

                wp_enqueue_script('maxpressy-dotimeout');
                wp_enqueue_script('maxboxy');
                wp_enqueue_script('maxboxy-pro'); // if pro version is active


                if (class_exists('Max_Boxy_Splitter')) {
                    wp_enqueue_script('maxboxy-splitter');
                }

            }

        }


        /**
         * Register and Enqueue assets.
         *
         * @return void Registering and enqueueing assets.
         */
        // phpcs:ignore
        public static function scripts_and_styles()
        {

            if (! is_admin()) {

                wp_register_style('maxboxy', plugins_url('/library/css/min/main.css', dirname(__FILE__)), array(), MAXBOXY_VERSION, 'all');

                // dotimeout with prefix 'maxpressy-' coz other plugins can depend on the same script
                wp_register_script('maxpressy-dotimeout', plugins_url('/library/js/min/do-timeout.js', dirname(__FILE__)), array('jquery'), MAXBOXY_VERSION, array('strategy' => 'defer'));

                wp_register_script('maxboxy', plugins_url('/library/js/min/main.js', dirname(__FILE__)), array('jquery', 'maxpressy-dotimeout'), MAXBOXY_VERSION, array('strategy' => 'defer'));
                wp_register_script('maxboxy-conversions', plugins_url('/library/js/min/conversions.js', dirname(__FILE__)), array('jquery', 'maxpressy-dotimeout', 'maxboxy'), MAXBOXY_VERSION, array('strategy' => 'defer'));

                // for debugging:
                //wp_register_script('maxboxy', plugins_url('/library/js/src/main.js', dirname(__FILE__)), array('jquery', 'maxpressy-dotimeout'), MAXBOXY_VERSION, true);
                //wp_register_script('maxboxy-conversions', plugins_url('/library/js/src/conversions.js', dirname(__FILE__)), array('jquery', 'maxpressy-dotimeout', 'maxboxy'), MAXBOXY_VERSION, true);

                if (self::enqueue_place() !== 'on_demand') {

                    wp_enqueue_style('maxboxy');

                    // make sure jquery is loading
                    wp_enqueue_script('jquery');

                    wp_enqueue_script('maxpressy-dotimeout');
                    wp_enqueue_script('maxboxy');

                    if (Max_Boxy_Track::enabled() === true) {
                        wp_enqueue_script('maxboxy-conversions');
                    }

                }

                // add local vars for translation, accesed through the JS
                $local_var_array  = array(
                    'toggler_title_open'  => esc_html__('Open', 'maxboxy'),
                    'toggler_title_close' => esc_html__('Close', 'maxboxy'),
                    'ajax_url'            => admin_url('admin-ajax.php'),
                    'mb_nonce'            => wp_create_nonce('mb-nonce'),
                );

                wp_localize_script('maxboxy', 'maxboxy_localize', $local_var_array);

                // large screen break point - add inline
                $large_screen_break_point   =  isset(get_option('_maxboxy_options')[ 'large_screen_break_point' ])
                                            ?  (int)(get_option('_maxboxy_options')[ 'large_screen_break_point' ] ) : '';

                if (is_numeric($large_screen_break_point) && $large_screen_break_point !== 992) {
                    $script = 'var new_large_screen_break_point = ' .esc_attr($large_screen_break_point) .';';
                    wp_add_inline_script('maxboxy', $script, false);

                }

            }

        }


        /**
         * License not active notice.
         *
         * @return string.
         */
        // phpcs:ignore
        public static function _safe_license_notactive_notice()
        {

            $_escaped_message =  '<p>' .esc_html('Please do activate your MaxBoxy Pro license ', 'maxboxy') .'<a href="' .esc_url(admin_url('admin.php?page=maxboxy-licenses')) .'" target="_self">' .__('here', 'maxboxy') .'</a>'
            .esc_html(' to gain access to premium features. Most likely you see this message because your license has expired or you deactivated it. Important: Saving the options without the license active will cause in losing the previously saved premium settings.', 'maxboxy') .'</p>';

             return $_escaped_message;

        }


        /**
         * Set admin notices.
         *
         * Extends _safe_license_notactive_notice() based on the
         * certain pages condtion.
         *
         * @return string.
         */
        // phpcs:ignore
        public static function admin_notices()
        {

            $get_license = class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== '' ? true : false;

            // if the Pro version is active but the license not actvated
            if (class_exists('Max_Boxy_Pro') && $get_license === false) {

                $current_screen  = get_current_screen()->base;
                $curent_post_id  = isset($_GET['post'])      ? $_GET['post'] : '';
                $settings        = ! empty($curent_post_id)  ? get_post_meta($curent_post_id, '_mb_floatany', true) : '';

                // show the message for the following pages: if the current screen is maxboxy settings page || if it is 'post' page
                if ($current_screen === 'admin_page_maxboxy-settings' || ! empty($settings)) {

                    echo '<div class="notice is-dismissible notice-warning splitters-remove-notice">' .self::_safe_license_notactive_notice() .'</div>';

                } else {
                    return;
                }

            }

        }


        /**
         * Add admin menu items.
         * 
         * @return void Hooking to the add_menupage and add_submenu_page functions.
         */
        // phpcs:ignore
        public static function admin_menu()
        {

            add_menu_page('MaxBoxy', 'MaxBoxy', 'edit_pages', 'admin.php?page=maxboxy-settings', false, 'dashicons-layout', 80);
            add_submenu_page('admin.php?page=maxboxy-settings', '', 'Settings', 'manage_options', 'admin.php?page=maxboxy-settings', false);

            $panel_label = __('Panels: ', 'maxboxy');
            add_submenu_page('admin.php?page=maxboxy-settings', '', $panel_label .'InjectAny', 'edit_pages', 'edit.php?post_type=inject_any', false);
            add_submenu_page('admin.php?page=maxboxy-settings', '', $panel_label .'FloatAny',  'edit_pages', 'edit.php?post_type=float_any',  false);

            if (Max_Boxy_Reusable_Blocks::enabled() === true) {
                /*
                 * Add Reusable blocks as a main Menu item, coz adding any additonal subitem to
                 * the MaxBoxy makes an issue when on Cat and Tags page (needs clicking twice
                 * on a link to get to another page).
                 */
                add_menu_page('reusableblocks', 'Synced Patterns', 'edit_pages',  'edit.php?post_type=wp_block', false, 'dashicons-layout', 80);
            }

            add_submenu_page('admin.php?page=maxboxy-settings', '', __('Manage Categories', 'maxboxy'), 'edit_pages',  'edit-tags.php?taxonomy=maxboxy_cat', false);
            add_submenu_page('admin.php?page=maxboxy-settings', '', __('Manage Tags', 'maxboxy'), 'edit_pages',  'edit-tags.php?taxonomy=maxboxy_tag', false);

            /*
             * Add an empty (fake) admin item, so the number of menu items is the same
             * when the Licence info item is present (i.e. with Pro) and without it as well.
             * ...This way current item will be properly marked.
             * 
             * Do not exclude it when the pro isn't active, i.e. ! class_exists( 'Max_Boxy_Pro' ),
             * coz, while for Admins it would be the good choice, with Editors it make a problem,
             * i.e. when there's no "License" link in the menu.
             * ...Basically, this way it works for all roles in combination with JS code.
             */
            add_submenu_page('admin.php?page=maxboxy-settings', '', '', 'edit_pages',  '', false);

        }


        /**
         * Post type - inject_any.
         *
         * @return void Hooking to the register_post_type().
         */
        // phpcs:ignore
        public static function set_post_type_inject_any()
        {

            $labels = array(
                'name'                => __('InjectAny Panels',        'maxboxy'),
                'singular_name'       => __('InjectAny Panel',         'maxboxy'),
                'all_items'           => __('InjectAny items',         'maxboxy'),
                'add_new_item'        => __('Add New Panel',           'maxboxy'),
                'add_new'             => __('Add New',                 'maxboxy'),
                'edit_item'           => __('Edit InjectAny Panel',    'maxboxy'),
                'update_item'         => __('Update InjectAny Panel',  'maxboxy'),
                'search_items'        => __('Search InjectAny Panels', 'maxboxy'),
                'not_found'           => __('Not found',               'maxboxy'),
                'not_found_in_trash'  => __('Not found in Trash',      'maxboxy'),
            );

            $args = array(
                'labels'              => $labels,
                'supports'            => array( 'title', 'editor' ),
                'show_in_rest'        => true,
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => false,
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'rewrite'             => false,
            );
            register_post_type('inject_any', $args);

        }


        /**
         * Post type - float_any.
         * 
         * @return void Hooking to the register_post_type().
         */
        // phpcs:ignore
        public static function set_post_type_float_any()
        {

            $labels = array(
                'name'                => __('FloatAny Panels',         'maxboxy'),
                'singular_name'       => __('FloatAny Panel',          'maxboxy'),
                'all_items'           => __('FloatAny items',          'maxboxy'),
                'add_new_item'        => __('Add New Panel',           'maxboxy'),
                'add_new'             => __('Add New',                 'maxboxy'),
                'edit_item'           => __('Edit FloatAny Panel',     'maxboxy'),
                'update_item'         => __('Update FloatAny Panel',   'maxboxy'),
                'search_items'        => __('Search FloatAny Panels',  'maxboxy'),
                'not_found'           => __('Not found',               'maxboxy'),
                'not_found_in_trash'  => __('Not found in Trash',      'maxboxy'),
            );

            $args = array(
                'labels'              => $labels,
                'supports'            => array( 'title', 'editor' ),
                'taxonomies'          => array( 'maxboxy_cat', 'maxboxy_tag' ),
                'show_in_rest'        => true,
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => false,
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'rewrite'             => false,
            );
            register_post_type('float_any', $args);

        }


        /**
         * Taxonomies.
         * 
         * @return void Hooking to the register_taxonomy().
         */
        // phpcs:ignore
        public static function set_taxonomies()
        {

            $labels_cats = array(
                'name'            => __('Categories',        'maxboxy'),
                'singular_name'   => __('Category',          'maxboxy'),
            );

            $args_cats = array(
                'labels'            => $labels_cats,
                'public'            => true,
                'publicly_queryable'=> false,
                'rewrite'           => false,
                'hierarchical'      => true,
                'show_in_rest'      => true,
                'show_admin_column' => true,
                'show_ui'           => true,
                'show_in_menu'      => false,
            );

            $labels_tag = array(
                'name'             => __('Tags',        'maxboxy'),
                'singular_name'    => __('Tag',         'maxboxy'),
                'add_new_item'     => __('Add New Tag', 'maxboxy'),
            );

            $args_tags = array(
                'labels'            => $labels_tag,
                'public'            => true,
                'publicly_queryable'=> false,
                'rewrite'           => false,
                'hierarchical'      => false,
                'show_in_rest'      => true,
                'show_admin_column' => true,
                'show_ui'           => true,
                'show_in_menu'      => false,
            );

            $add_reusable_blocks = Max_Boxy_Reusable_Blocks::enabled() === true ? 'wp_block' : '';

            register_taxonomy('maxboxy_cat', array( 'inject_any', 'float_any', $add_reusable_blocks ), $args_cats);
            register_taxonomy('maxboxy_tag', array( 'inject_any', 'float_any', $add_reusable_blocks ), $args_tags);

        }


        /**
         * Query float_any post type.
         *
         * Additionally, it excludes 'auto_loading' with value 'disabled'.
         * Means, that it gets only the items that are globally loading.
         * 
         * @return string Requested posts.
         */
        // phpcs:ignore
        private static function query_float_any()
        {

            $set_args = array(
                'post_type'   => 'float_any',
                'numberposts' => -1,
                'post_status' => 'publish',
                'meta_query'  => array(
                    array(
                        'key' => 'auto_loading',
                        'value' => 'disabled',
                        'compare' => 'NOT LIKE',
                    ),
                ),
            );

            return $set_args;

        }


        /**
         * Query inject_any post type - head location.
         *
         * Additionally, it excludes 'auto_loading' with value 'disabled'.
         * Means, that it gets only the items that are globally loading.
         *
         * @return string Requested posts.
         */
        // phpcs:ignore
        private static function query_inject_any_head()
        {

            $args = array(
                'post_type'   => 'inject_any',
                'numberposts' => -1,
                'post_status' => 'publish',
                'orderby'     => 'modified',
                'meta_query'  => array(
                    array(
                        'key' => 'auto_loading',
                        'value' => 'disabled',
                        'compare' => 'NOT LIKE',
                    ),
                    array(
                        'key' => 'location',
                        'value' => 'head',
                        'compare' => 'LIKE',
                    ),
                ),
            );

            return $args;

        }


        /**
         * Query inject_any post type - top location.
         *
         * Additionally, it excludes 'auto_loading' with value 'disabled'.
         * Means, that it gets only the items that are globally loading.
         *
         * @return string Requested posts.
         */
        // phpcs:ignore
        private static function query_inject_any_top()
        {

            $args = array(
                'post_type'   => 'inject_any',
                'numberposts' => -1,
                'post_status' => 'publish',
                'orderby'     => 'modified',
                'meta_query'  => array(
                    array(
                        'key' => 'auto_loading',
                        'value' => 'disabled',
                        'compare' => 'NOT LIKE',
                    ),
                    array(
                        'key' => 'location',
                        'value' => 'top',
                        'compare' => 'LIKE',
                    ),
                ),
            );

            return $args;

        }


        /**
         * Query inject_any post type - bottom location.
         *
         * Additionally, it excludes 'auto_loading' with value 'disabled'.
         * Means, that it gets only the items that are globally loading.
         * 
         * @return string Requested posts.
         */
        // phpcs:ignore
        private static function query_inject_any_bottom()
        {

            $args = array(
                'post_type'   => 'inject_any',
                'numberposts' => -1,
                'post_status' => 'publish',
                'orderby'     => 'modified',
                'meta_query'  => array(
                    array(
                        'key' => 'auto_loading',
                        'value' => 'disabled',
                        'compare' => 'NOT LIKE',
                    ),
                    array(
                        'key' => 'location',
                        'value' => 'bottom',
                        'compare' => 'LIKE',
                    ),
                ),
            );

             return $args;

        }


        /**
         * Process each panel, i.e. return each panel.
         *
         * @param int     $get_id       Receive the id of the panel.
         * @param object  $post         Receive the WP_Post object of the current post (panel).
         * @param boolean $is_shorty    Whether the current panel is processed as shortcode.
         * @param boolean $is_ajax_call Whether the current panel is processed from Ajax.
         * @param string  $name         In shortcode it is differently constracted then in inject_any|float_any loops. Otherwise $name would be here.
         * @param array   $loading      params from the Max_Boxy_Options::loading().
         * @param array   $basics       Array of params from the Max_Boxy_Options::basics().
         * @param array   $goals        Array of params from the Max_Boxy_Options::conversions().
         * @param array   $conditionals Array of params from the funtions of Max_Boxy_Conditionals class.
         * @param array   $splitter     Array of params from the Max_Boxy_Splitter::panel_options().
         * 
         *                              $loading of @param {
         *
         * @type boolean 'global'       Whether a panel's global (auto) loading, i.e. over the whole site is enabled or disabled.
         * @type string  'location'     In which location the panel is loaded.
         * @type boolean 'test_mode'    Load the panel only for the Admins with edit_posts privileges.
         *
         * }
         *
         *                              $basics of @param {
         * @type string  'type'                      The type of the panel e.g. closer|toggler.
         * @type string  'strain'                    Class of the panel's strain e.g. floatany|injectany.
         * @type string  'style'                     The appearance style of the panel e.g. bump|slide-vertical|etc.
         * @type string  'roles'                     The role of the panel e.g. role-hidden|role-exit|role-igniter|etc.
         * @type string  'rotator_repeat'            Set the class for rotator repeatition, otherwise it's empty.
         * @type string  'trig_class'                Based on the panel's type, set the class for panel's closing button.
         * @type string  'add_classes'               Add additional classes to the panel.
         * @type string  'panel_size'                Panel size class.
         * @type string  'direction'                 Class detirmens the relation direction of content box and toggler/closer button.
         * @type string  'closer_align'              Class detirmens the alignment of toggler/closer button.
         * @type string  'closer_size'               Class detirmens the size of toggler/closer button.
         * @type string  'toggler_styling'           Additional toggler/closer styling e.g. border|inside|etc.
         * @type string  'toggler_start_class'       Starting button class depending on is it igniter or not, iks-plus|minus|etc.
         * @type string  'trigger_add_message_class' Set the class for trigger's additional message.
         * @type string  'toggler_svg_classes'       Set the trigger svg classes
         * @type string  'toggler_img_classes'       Set the trigger img classes
         * @type string  'trigger_anim'              Set the trigger animation classes
         * @type string  'unset_toggler_class'       Additional class for unsetting the toggler
         * @type string  'trigger_icon_classes'      Set the trigger icon classes
         * @type string  'panel_add_lable_class'     Set the class for trigger's additional message.
         * @type string  'injectany_align'           Alignment class for InjectAny.
         * @type string  'sticky'                    For InjectAny, print a class setting the panel as sticky, otherwise it's empty.
         * @type string  'no_margin_closer'          Set a class to eliminate the margin on the closer button.
         * @type string  'anim_echo_time'            Escaped - For anim echo time data.
         * @type string  'rotator_time'              Escaped - For role rotator time data.
         * @type string  'wrap_style'                Escaped - get style attribute with its values for the .mboxy-wrap div.
         * @type string  'panel_style'               Escaped - get style attribute with its values for the .mboxy div.
         * @type string  'content_style'             Escaped - get style attribute with its values for the .mboxy-content div.
         * @type string  'trig_style'                Escaped - get style attribute with its values for the trig button.
         * @type string  'trig_message_style'        Escaped - get style attribute with its values for the trig button's additional message and label.
         * @type string  'toggler_data'              Escaped - get data attribute with its values for the toggler/closer button.
         * @type string  'trigger_add_message'       Set the trigger button's additional message.
         * @type string  'trig_svg_open'             Safe - svg for the toggler's opening.
         * @type string  'trig_svg_close'            Safe - svg for the toggler's closing.
         * @type string  'trig_img_open'             Escaped - img for the toggler's opening.
         * @type string  'trig_img_close'            Escaped - img for the toggler's closing.
         * @type string  'panel_add_lable'           Set the panel's additional message.
         * @type string  'unset_toggler'             Wheather to unset toggler/closer button. Default 'no'. Further accepts 'closer', 'all'.
         * @type string  'toggler_start_title'       Title attriblute's value for the toggler/closer button. Default 'Close'. If the panel is role-igniter switch value to 'Open'.
         * @type boolean 'use_overlay'               Wheather to use the overlay.
         *
         * }
         *
         *                              $goals of @param {
         * @type string 'goalset'                If the goal is set, print its value
         * @type string 'banish'                 Set the class to prevent the panel appearing again if the goal is complete.
         * @type string 'goal_data'              Escaped data attribute with its values for the set goal.
         * 
         * }
         *
         *                              $conditionals of @param {
         * @type boolean 'page_pass'             Wheather the condition is affecting the current page. Default 'true', means print the panel. Accepts 'false', i.e. do not print the panel.
         * @type string 'schedule'               Whether the panel is under the schedule campaign. Default ''. Accepts values 'on' print the panel, 'stop' if the time span isn't met
         * @type boolean 'appear_pass'           Whether the panel is passing the appearance trigger check. Default 'true'. Accepts 'false', i.e. do not print the panel.
         * @type string 'appear_classes'         Print the classes for appearance trigger.
         * @type string 'appear_data'            Escaped data attribute with its values for the set appearance triggers.
         *
         * }
         *
         *                              $splitter of @param {
         * @type boolean 'on'                    Wheather the panel is under the splitter campaign. Default 'false'. Accepts 'true'.
         * @type string 'classes'                Set the split class.
         * @type boolean 'prerender'             Wheather the split panel is prerendered, sets the Ajax behavior @see Max_Boxy_Splitter::serve_splitter_panel_ajax(). Default 'false'. Accepts 'true'.
         * @type int 'id'                        Splitter id.
         * 
         * }
         *
         * @return string Outputs the panel.
         */
        public static function panel( $get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter )
        {

            /**
             * Set the Content output
             * Instead of apply_shortcodes( $get_content ), 
             * it's better to use apply_filters on the_content,
             * which will process all gutenberg's blocks e.g. iframes in html content
             */
            $get_content  = ! empty($post->post_content) ? $post->post_content : '';
            $_set_content = apply_filters('the_content', $get_content);

            /**
             * If the title isn't specified, set the post id
             * This is to bypass the cunfusion, when a user omits the title
             */
            $name = empty($name) ? $get_id : $name;

            // Stop - if it's splitted item and it isn't prerendered (it will be processed through the ajax)
            if ($is_ajax_call === false && $splitter[ 'on' ] === true && $splitter[ 'prerender' ] === false ) {
                return;
            }


            /**
             * $loading[ 'location' ] and $loading[ 'test_mode' ] is null for
             * 'wp_block' and browser would throw a warning notice,
             * so make this check
             */
            $location        = ! empty($loading[ 'location' ]) ? $loading[ 'location' ]  : '';
            $test_mode       = isset($loading[ 'test_mode' ])  ? $loading[ 'test_mode' ] : '';

            $user_privileges = current_user_can('edit_posts') ? true : false;

            // If test mode is on and user doesn't have enough previlieges - stop
            if (! empty($test_mode) && $user_privileges === false) {
                return;
            }

            $_escaped_out = '';

            if (! empty($_set_content) && $conditionals[ 'page_pass' ] !== false && $conditionals[ 'appear_pass' ] !== false && $conditionals[ 'schedule' ] !== 'stop') {

                // dependant on the panel id: splitter, tracking(load, views, goals)
                $_escaped_panel_id = Max_Boxy_Track::enabled() === true || $splitter[ 'on' ] === true || ! empty($goals[ 'goalset' ])
                                   ? ' data-panel-id="' .esc_attr($get_id) .'"' : '';

                $stats_enabled     = Max_Boxy_Track::enabled() === true ? ' stats-on' : '';
                $shorty_class      = $is_shorty === true ? ' shortcode-made' : '';

                /*
                 * Output the "head" location.
                 */
                if (get_post_type($get_id) === 'inject_any' && $location === 'head') {
                    return $_set_content;
                }

                /**
                 * Output the panel i.e. all other locations than injectany's head.
                 */
                $_escaped_out .= '<div id="' .esc_attr($name)
                                .'" class="mboxy-wrap'
                                    .esc_attr($basics[ 'strain' ])
                                    .esc_attr($basics[ 'injectany_align' ])
                                    .esc_attr($shorty_class)
                                    .esc_attr($location)
                                    .esc_attr($basics[ 'style' ])
                                    .esc_attr($basics[ 'roles' ])
                                    .esc_attr($basics[ 'rotator_repeat' ])
                                    .esc_attr($basics[ 'trig_class' ])
                                    .esc_attr($basics[ 'unset_toggler_class' ])
                                    .esc_attr($basics[ 'sticky' ])
                                    .esc_attr($basics[ 'add_classes' ])
                                    .esc_attr($goals[ 'goalset' ])
                                    .esc_attr($goals[ 'banish' ])
                                    .esc_attr($conditionals[ 'appear_classes' ])
                                    .esc_attr($splitter[ 'classes' ])
                                    .esc_attr($stats_enabled)
                                .'"'
                                // the following is all already escaped @see Max_Boxy_Options::basics()
                                .$_escaped_panel_id
                                .$basics[ 'wrap_style' ]
                                .$basics[ 'rotator_time' ]
                                // the following is all already escaped @see Max_Boxy_Options::conversions()
                                .$goals[ 'goal_data' ]
                                // $conditionals[ 'appear_data' ] is already escaped @see Max_Boxy_Conditionals::appear_triggers()
                                .$conditionals[ 'appear_data' ]
                                .'>';

                // The following are already escaped @see Max_Boxy_Options::basics()
                $_escaped_anim_echo                = $basics[ 'anim_echo_time' ];
                $_escaped_early_trig_style         = $basics[ 'trig_style' ];
                $_escaped_early_trig_message_style = $basics[ 'trig_message_style' ];
                $_escaped_early_panel_style        = $basics[ 'panel_style' ];
                $_escaped_early_toggler_data       = $basics[ 'toggler_data' ];

                $_escaped_out .= '<div class="mboxy' .esc_attr($basics[ 'panel_size' ]) .esc_attr($basics[ 'direction' ]) .esc_attr($basics[ 'panel_add_lable_class' ]) .esc_attr($basics[ 'closer_align' ]) .'"' .$_escaped_early_panel_style .'>';

                $_escaped_label = ! empty($basics[ 'panel_add_lable' ]) ? $_escaped_panel_additional_lable = '<div class="additional-label"' .$_escaped_early_trig_message_style .'>' .esc_html($basics[ 'panel_add_lable' ]) .'</div>' : '';

                //$_escaped_out .= $_set_content; // $_set_content is the content of the WP post
                $_escaped_out .= '<div class="mboxy-content"' .$basics[ 'content_style' ]  .'>' .$_set_content .$_escaped_label .'</div>'; // $_set_content is the content of the WP post

                $basic_trigger_classes = 'trigger trig-default';

                // $basics[ 'trig_img_open' ], $basics[ 'trig_img_close' ], $basics[ 'trig_svg_open' ] and $basics[ 'trig_svg_close' ]
                // are already escaped @see Max_Boxy_Options::basics()
                $_escaped_early_group = $basics[ 'trig_svg_open' ] .$basics[ 'trig_svg_close' ] .$basics[ 'trig_img_open' ] .$basics[ 'trig_img_close' ];
                
                // Additional message on a trigger button or a panel
                $_escaped_trigger_add_message = ! empty($basics[ 'trigger_add_message' ]) ?  '<div class="additional-message"' .$_escaped_early_trig_message_style .'><span class="additional-message-killer" title="' .esc_html('Close', 'maxboxy') .'">x</span><span class="additional-message-content">' .esc_html($basics[ 'trigger_add_message' ]) .'</span></div>' : '';
                $_escaped_trig_icon = ! empty($basics[ 'trigger_icon_classes' ]) ? '<div class="trig-icon' .esc_attr($basics[ 'toggler_start_class' ]) .'"' .$_escaped_early_toggler_data .'></div>' : '';

                // closer && if closer isn't disabled
                if ($basics[ 'type' ] === 'closer' && $basics[ 'unset_toggler' ] === 'no') {

                    $_escaped_out .= '<div class="mboxy-closer '
                        .esc_attr($basic_trigger_classes)
                        .esc_attr($basics[ 'closer_size' ])
                        .esc_attr($basics[ 'trigger_icon_classes' ])
                        .esc_attr($basics[ 'trigger_add_message_class' ])
                        .esc_attr($basics[ 'toggler_svg_classes' ])
                        .esc_attr($basics[ 'toggler_img_classes' ])
                        .esc_attr($basics[ 'trigger_anim' ])
                        .esc_attr($basics[ 'toggler_styling' ]) .'" title="' .__('Close', 'maxboxy') .'"' .$_escaped_anim_echo .$_escaped_early_trig_style .'>'
                        .$_escaped_trigger_add_message
                        .$_escaped_early_group
                        .$_escaped_trig_icon
                    .'</div>';

                }

                // toggler/igniter && if it isn't disabled
                if ($basics[ 'type' ] === 'toggler' && $basics[ 'unset_toggler' ] !== 'all') {

                    $_escaped_out .= '<div class="mboxy-toggler '
                                        .esc_attr($basic_trigger_classes)
                                        .esc_attr($basics[ 'closer_size' ])
                                        .esc_attr($basics[ 'trigger_icon_classes' ])
                                        .esc_attr($basics[ 'trigger_add_message_class' ])
                                        .esc_attr($basics[ 'toggler_svg_classes' ])
                                        .esc_attr($basics[ 'toggler_img_classes' ])
                                        .esc_attr($basics[ 'trigger_anim' ])
                                        .esc_attr($basics[ 'no_margin_closer' ])
                                        .esc_attr($basics[ 'toggler_styling' ]) .'" title="' .esc_attr($basics[ 'toggler_start_title' ]) .'"' .$_escaped_anim_echo .$_escaped_early_trig_style .'>'
                                        .$_escaped_trigger_add_message
                                        .$_escaped_early_group
                                        .$_escaped_trig_icon
                                    .'</div>';

                }

                $_escaped_out .= '</div>'; // close .mboxy
                
                // overlay
                if ($basics[ 'use_overlay' ] === true) {
                    $_escaped_out .= '<div class="mboxy-overlay"><div class="overlay-inner" title="' .__('Close', 'maxboxy') .'"></div></div>';
                }
                
                $_escaped_out .= '</div>'; // close .mboxy-wrap

            }

            return $_escaped_out;

        }


        /**
         * Gather the result of injectany Query, for head location,
         * and loop through the result.
         *
         * @return string.
         */
        // phpcs:ignore
        public static function loop_injectany_head()
        {

            $args       = self::query_inject_any_head();

            $get_posts  = get_posts($args);

            $panels_out =   array();

            foreach ( $get_posts as $post ) : setup_postdata($post);

                // $get_id var has to be the same as at shortcodes
                $get_id         = $post->ID; 
                $is_shorty      = false;
                $is_ajax_call   = false;
                $name           = sanitize_title($post->post_title);

                $splitter       = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true
                                ? Max_Boxy_Splitter::panel_options($get_id)
                                : array( 'on' => false, 'classes' => '', 'prerender' => false );

                $loading        = Max_Boxy_Options::loading($get_id);
                $basics         = Max_Boxy_Options::basics($get_id);
                $goals          = Max_Boxy_Options::conversions($get_id);

                $conditionals_on= class_exists('Max_Boxy_Conditionals') ? true : false;
                $conditionals   = array(
                    'page_pass'  => $conditionals_on === true ? Max_Boxy_Conditionals::pages($get_id) : true,
                    'schedule'   => $conditionals_on === true ? Max_Boxy_Conditionals::schedule($get_id) : '',
                    // Max_Boxy_Conditionals::appear_triggers returns array:
                    'appear_pass'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_check' ]   : true,
                    'appear_classes' => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_classes' ] : '',
                    'appear_data'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_data' ]    : '',
                );


                // envoke each panel
                $panel = self::panel($get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter);

                $panels_out[] = ! empty($panel) ? $panel : '';

            endforeach;
            wp_reset_postdata();

            // join all the panels
            $_safe_panels_group = ! empty($panels_out) ? implode('', $panels_out) : '';

            // output
            if (! empty($_safe_panels_group)) {

                // safe to output, all escapped in @see function panel
                echo $_safe_panels_group;

            }

        }

        /**
         * Gather the result of injectany Query, for top location,
         * and loop through the result.
         *
         * @return string.
         */
        // phpcs:ignore
        public static function loop_injectany_top()
        {

            $args       = self::query_inject_any_top();

            $get_posts  = get_posts($args);

            $panels_out = array();

            foreach ($get_posts as $post) : setup_postdata($post);

                $get_id         = $post->ID; // var has to be the same as at shortcodes
                $is_shorty      = false;
                $is_ajax_call   = false;
                $name           = sanitize_title($post->post_title);

                $splitter       = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true
                                ? Max_Boxy_Splitter::panel_options($get_id)
                                : array( 'on' => false, 'classes' => '', 'prerender' => false );

                $loading        = Max_Boxy_Options::loading($get_id);
                $basics         = Max_Boxy_Options::basics($get_id);
                $goals          = Max_Boxy_Options::conversions($get_id);

                $conditionals_on= class_exists('Max_Boxy_Conditionals') ? true : false;
                $conditionals   = array(
                    'page_pass'  => $conditionals_on === true ? Max_Boxy_Conditionals::pages($get_id)    : true,
                    'schedule'   => $conditionals_on === true ? Max_Boxy_Conditionals::schedule($get_id) : '',
                    // Max_Boxy_Conditionals::appear_triggers returns array:
                    'appear_pass'     => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_check' ]   : true,
                    'appear_classes'  => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_classes' ] : '',
                    'appear_data'     => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_data' ]    : '',
                );


                // envoke each panel
                $panel = self::panel($get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter);

                $panels_out[] = ! empty($panel) ? $panel : '';

            endforeach;
            wp_reset_postdata();

            // join all the panels
            $_safe_panels_group = ! empty($panels_out) ? implode('', $panels_out) : '';

            // output
            if (! empty($_safe_panels_group)) {

                // safe to output, all escapped in @see function panel
                echo $_safe_panels_group;

                self::enqueue_place_on_demand();

            }

        }


        /**
         * Gather the result of injectany Query, for bottom location,
         * and loop through the result.
         *
         * @return string.
         */
        // phpcs:ignore
        public static function loop_injectany_bottom()
        {

            $args       = self::query_inject_any_bottom();
            $get_posts  = get_posts($args);
            $panels_out = array();

            foreach ( $get_posts as $post ) : setup_postdata($post);

                $get_id         = $post->ID; // var has to be the same as at shortcodes
                $is_shorty      = false;
                $is_ajax_call   = false;
                $name           = sanitize_title($post->post_title);

                $splitter       = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true
                                ? Max_Boxy_Splitter::panel_options($get_id)
                                : array( 'on' => false, 'classes' => '', 'prerender' => false );

                $loading        = Max_Boxy_Options::loading($get_id);
                $basics         = Max_Boxy_Options::basics($get_id);
                $goals          = Max_Boxy_Options::conversions($get_id);

                $conditionals_on= class_exists('Max_Boxy_Conditionals') ? true : false;
                $conditionals   = array(
                    'page_pass'  => $conditionals_on === true ? Max_Boxy_Conditionals::pages($get_id)    : true,
                    'schedule'   => $conditionals_on === true ? Max_Boxy_Conditionals::schedule($get_id) : '',
                    // Max_Boxy_Conditionals::appear_triggers returns array:
                    'appear_pass'     => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_check' ]   : true,
                    'appear_classes'  => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_classes' ] : '',
                    'appear_data'     => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_data' ]    : '',
                );


                // envoke each panel
                $panel = self::panel($get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter);

                $panels_out[] = ! empty($panel) ? $panel : '';

            endforeach;
            wp_reset_postdata();

            // join all the panels
            $_safe_panels_group = ! empty($panels_out) ? implode('', $panels_out) : '';

            // output
            if (! empty($_safe_panels_group)) {

                // safe to output, all escapped in @see function panel
                echo $_safe_panels_group;

                self::enqueue_place_on_demand(); 

            }

        }


        /**
         * Gather the result of float_any Query, 
         * and loop through the result.
         *
         * @return string.
         */
        // phpcs:ignore
        public static function loop_floatany()
        {

            $args       = self::query_float_any();
            $get_posts  = get_posts($args);
            $panels_out = array();

            foreach ( $get_posts as $post ) : setup_postdata($post);

                $get_id         = $post->ID; // var has to be the same as at shortcodes
                $is_shorty      = false;
                $is_ajax_call   = false;
                $name           = sanitize_title($post->post_title);

                $splitter       = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true
                                ? Max_Boxy_Splitter::panel_options($get_id)
                                : array( 'on' => false, 'classes' => '', 'prerender' => false );

                $loading        = Max_Boxy_Options::loading($get_id);
                $basics         = Max_Boxy_Options::basics($get_id);
                $goals          = Max_Boxy_Options::conversions($get_id);

                $conditionals_on= class_exists('Max_Boxy_Conditionals') ? true : false;
                $conditionals   = array(
                    'page_pass'  => $conditionals_on === true ? Max_Boxy_Conditionals::pages($get_id)    : true,
                    'schedule'   => $conditionals_on === true ? Max_Boxy_Conditionals::schedule($get_id) : '',
                    // Max_Boxy_Conditionals::appear_triggers returns array:
                    'appear_pass'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_check' ]   : true,
                    'appear_classes' => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_classes' ] : '',
                    'appear_data'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_data' ]    : '',
                );

                // envoke each panel
                $panel = self::panel($get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter);

                $panels_out[] = ! empty($panel) ? $panel : '';

            endforeach;
            wp_reset_postdata();

            // join all the panels
            $_safe_panels_group = ! empty($panels_out) ? implode('', $panels_out) : '';

            // output
            if (! empty($_safe_panels_group)) {

                // safe to output, all escapped in @see function panel
                echo '<div id="floatany-wrapper" class="floatany-wrapper">' .$_safe_panels_group .'</div>';

                self::enqueue_place_on_demand();

            }

        }


    } // end class

} // end class exists check
